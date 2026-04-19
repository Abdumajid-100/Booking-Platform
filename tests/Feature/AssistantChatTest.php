<?php

use App\Models\Business;
use App\Models\BusinessesTypes;
use App\Models\Service;
use App\Models\User;

it('renders the assistant page', function () {
    $this->get(route('assistant'))
        ->assertOk()
        ->assertSee('BroNix AI')
        ->assertSee('Диалоговый режим');
});

it('returns local booking recommendations from the business catalog', function () {
    config(['services.gemini.key' => null]);

    $owner = User::factory()->create();
    $type = BusinessesTypes::create(['name' => 'Барбершоп']);

    $budgetFriendly = Business::create([
        'user_id' => $owner->id,
        'businesses_type_id' => $type->id,
        'name' => 'Trim Room',
        'description' => 'Быстрые мужские стрижки рядом с центром.',
        'address' => 'Ташкент, улица Амира Темура',
        'phone' => '+998 90 000 00 01',
    ]);

    $premium = Business::create([
        'user_id' => $owner->id,
        'businesses_type_id' => $type->id,
        'name' => 'Premium Cut',
        'description' => 'Премиальный уход и расширенный сервис.',
        'address' => 'Ташкент, улица Шахрисабз',
        'phone' => '+998 90 000 00 02',
    ]);

    Service::create([
        'business_id' => $budgetFriendly->id,
        'name' => 'Мужская стрижка',
        'price' => 90000,
        'duration' => '60 мин',
    ]);

    Service::create([
        'business_id' => $premium->id,
        'name' => 'Комплексный уход',
        'price' => 220000,
        'duration' => '90 мин',
    ]);

    $token = 'assistant-test-token';

    $response = $this
        ->withSession(['_token' => $token])
        ->withHeader('X-CSRF-TOKEN', $token)
        ->postJson(route('assistant.message'), [
            'message' => 'Подбери барбершоп до 100000',
        ]);

    $response->assertOk()
        ->assertJsonPath('source', 'local')
        ->assertJsonStructure([
            'reply',
            'cards',
            'suggestions',
            'source',
            'insights' => ['budget', 'category', 'compare', 'match_count'],
        ]);

    expect($response->json('reply'))->toContain('100 000 сум');
    expect(collect($response->json('cards'))->pluck('name')->all())->toContain('Trim Room');
});

it('keeps conversation context for follow-up questions', function () {
    config(['services.gemini.key' => null]);

    $owner = User::factory()->create();
    $barberType = BusinessesTypes::create(['name' => 'Барбершоп']);
    $salonType = BusinessesTypes::create(['name' => 'Салон красоты']);

    $budgetFriendly = Business::create([
        'user_id' => $owner->id,
        'businesses_type_id' => $barberType->id,
        'name' => 'Budget Barber',
        'description' => 'Бюджетный барбершоп рядом с метро.',
        'address' => 'Ташкент, улица Бунёдкор',
        'phone' => '+998 90 000 00 03',
    ]);

    $beautyStudio = Business::create([
        'user_id' => $owner->id,
        'businesses_type_id' => $salonType->id,
        'name' => 'Beauty Studio',
        'description' => 'Салон красоты для маникюра и ухода.',
        'address' => 'Ташкент, улица Нукус',
        'phone' => '+998 90 000 00 04',
    ]);

    Service::create([
        'business_id' => $budgetFriendly->id,
        'name' => 'Стрижка',
        'price' => 80000,
        'duration' => '60 мин',
    ]);

    Service::create([
        'business_id' => $beautyStudio->id,
        'name' => 'Маникюр',
        'price' => 110000,
        'duration' => '60 мин',
    ]);

    $token = 'assistant-history-token';

    $response = $this
        ->withSession(['_token' => $token])
        ->withHeader('X-CSRF-TOKEN', $token)
        ->postJson(route('assistant.message'), [
            'message' => 'а подешевле',
            'history' => [
                ['role' => 'user', 'content' => 'Подбери барбершоп'],
                ['role' => 'assistant', 'content' => 'Сейчас посмотрю варианты барбершопов.'],
            ],
        ]);

    $response->assertOk()
        ->assertJsonPath('source', 'local');

    expect(collect($response->json('cards'))->pluck('name')->all())->toContain('Budget Barber');
    expect($response->json('reply'))->toContain('Продолжаю подбор');
});
