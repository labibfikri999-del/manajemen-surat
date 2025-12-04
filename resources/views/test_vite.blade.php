<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Test Vite</title>
  @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-slate-50">
  <div class="p-6">
    <h1 class="text-3xl font-bold text-green-600">TEST VITE & TAILWIND — OK</h1>
    <p class="mt-4 text-slate-700">Jika kamu melihat teks berwarna & bergaya, build assets berhasil.</p>
  </div>
  <script>
    console.log('test_vite loaded');
  </script>
</body>
</html>
