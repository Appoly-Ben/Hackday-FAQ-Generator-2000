<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">

    <title>Hackday FAQ Generator 2000</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-900">
    <div id="app">
        <div class="container mx-auto mt-4">
            <h1 class="text-white text-6xl text-center">Hack Day Faq Generator 2000</h1>
            <div class="mt-8 grid grid-cols-2 gap-4">
                @foreach (App\Models\Faq::all() as $faq)
                    <div class="bg-white rounded-xl p-8">
                        <div class="mb-2">{{ $faq->question }}</div>
                        <hr>
                        <div class="mt-2">{{ $faq->answer }}</div>
                    </div>
                @endforeach
            </div>
            <div class="bg-white rounded-xl p-8 mt-6">
                <q-and-a></q-and-a>
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
