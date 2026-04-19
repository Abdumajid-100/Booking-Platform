@extends('owner.layouts.app')

@section('content')
    <div class="container py-5">

        <!-- Заголовок -->
        <div class="mb-4">
            <h2 class="fw-bold">💰 Мой кошелёк</h2>
            <p class="text-muted">Управляйте балансом и транзакциями</p>
        </div>

        <!-- Баланс -->
        <div class="card text-white mb-4" style="background: linear-gradient(135deg, #4e73df, #1cc88a); border-radius: 15px;">
            <div class="card-body">
                <h6 class="opacity-75">Текущий баланс</h6>
                <h2 class="fw-bold">
                    {{ auth()->user()->wallet->balance ?? 0 }} сомони
                </h2>
            </div>
        </div>

        <!-- Кнопки -->
        <div class="d-flex gap-3 mb-4">
            <button class="btn btn-primary w-50" onclick="toggle('deposit')">
                ➕ Пополнить
            </button>
            <button class="btn btn-danger w-50" onclick="toggle('withdraw')">
                ➖ Вывести
            </button>
        </div>

        <!-- Пополнение -->
        <div id="deposit" class="card mb-3 d-none shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Пополнение счёта</h5>

                <form method="POST" action="{{ route('wallet.deposit') }}">
                    @csrf
                    <input type="number" name="amount" class="form-control mb-3" placeholder="Введите сумму" required>

                    <button class="btn btn-primary w-100">
                        Пополнить баланс
                    </button>
                </form>
            </div>
        </div>

        <!-- Вывод -->
        <div id="withdraw" class="card mb-3 d-none shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Вывод средств</h5>

                <form method="POST" action="{{ route('wallet.withdraw') }}">
                    @csrf
                    <input type="number" name="amount" class="form-control mb-3" placeholder="Введите сумму" required>

                    <button class="btn btn-danger w-100">
                        Вывести деньги
                    </button>
                </form>
            </div>
        </div>

        <!-- Карта -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">💳 Привязать карту</h5>

                <form method="POST" action="{{ route('cards.store') }}">
                    @csrf

                    <input name="card_number" class="form-control mb-2" placeholder="0000 0000 0000 0000" required>
                    <input name="holder_name" class="form-control mb-2" placeholder="Имя владельца" required>
                    <input name="expiry" class="form-control mb-3" placeholder="MM/YY" required>

                    <button class="btn btn-success w-100">
                        Сохранить карту
                    </button>
                </form>
            </div>
        </div>

        <!-- История -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">📜 История транзакций</h5>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Сумма</th>
                        <th>Тип</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(auth()->user()->transactions as $tx)
                        <tr>
                            <td class="fw-semibold">{{ $tx->amount }}</td>

                            <td>
                                <span class="badge
                                    {{ $tx->type === 'deposit' ? 'bg-primary' : 'bg-danger' }}">
                                    {{ $tx->type }}
                                </span>
                            </td>

                            <td>
                                <span class="badge
                                    {{ $tx->status === 'success' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>

                            <td class="text-muted">
                                {{ $tx->created_at->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function toggle(id) {
            document.getElementById('deposit').classList.add('d-none');
            document.getElementById('withdraw').classList.add('d-none');
            document.getElementById(id).classList.toggle('d-none');
        }
    </script>

@endsection
