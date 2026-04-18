<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>AI Booking Bot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="h-screen flex flex-col">

    <!-- CHAT -->
    <div id="chat" class="flex-1 p-6 overflow-y-auto space-y-3">

        <div class="bg-white p-3 rounded-xl w-fit">
            Привет 👋 Напиши что хочешь найти
        </div>

    </div>

    <!-- RESULTS -->
    <div id="results" class="grid md:grid-cols-3 gap-3 p-4"></div>

    <!-- INPUT -->
    <div class="bg-white border-t p-4 flex gap-2">

        <input id="input"
               class="flex-1 border p-3 rounded-xl"
               placeholder="например: нас 2 и 100 сомони хотим поесть">

        <button onclick="send()"
                class="bg-blue-600 text-white px-4 rounded-xl">
            Отправить
        </button>

    </div>

</div>

<script>

    const chat = document.getElementById('chat');
    const results = document.getElementById('results');

    function msg(text,type='bot'){
        const d=document.createElement('div');

        d.className=type==='user'
            ?'bg-blue-600 text-white p-3 rounded-xl ml-auto w-fit'
            :'bg-white p-3 rounded-xl w-fit';

        d.innerText=text;
        chat.appendChild(d);
    }

    async function send(){

        const input=document.getElementById('input');
        const text=input.value;
        if(!text) return;

        msg(text,'user');
        input.value='';

        const res=await fetch('/api/chat',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({message:text})
        });

        const data=await res.json();

        msg(data.reply,'bot');

        render(data.results);
    }

    function render(items){

        results.innerHTML='';

        items.forEach(i=>{

            const c=document.createElement('div');

            c.className="bg-white p-4 rounded-xl shadow";

            c.innerHTML=`
        <div class="font-bold">${i.name}</div>
        <div>💰 ${i.price}</div>
        <div>⭐ ${i.rating}</div>
        <div class="text-sm text-gray-500">score ${i.score}</div>
        `;

            results.appendChild(c);
        });
    }

</script>
<script>
    const res = await fetch('/api/chat', {...});

    console.log(await res.clone().text());

    const data = await res.json();
</script>
<script>async function send() {

        try {

            const text = input.value;
            if (!text) return;

            msg(text,'user');

            const res = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            });

            const data = await res.json();

            console.log(data); // 🔥 DEBUG

            msg(data.reply || "нет ответа", 'bot');

            if (data.results) render(data.results);

        } catch (e) {
            console.error(e);
            msg("Ошибка сервера ❌", 'bot');
        }
    }
</script>
</body>
</html>
