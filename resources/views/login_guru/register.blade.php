<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Register</title>
</head>

<body class="bg-teal-50 flex items-center justify-center min-h-screen p-4">
  <main class="w-full max-w-5xl rounded-2xl flex shadow-2xl overflow-hidden">
    <section class="w-full md:w-1/2 bg-teal-500 rounded-l-2xl p-8 flex flex-col items-center justify-center">
      <h1 class="text-white font-bold text-3xl md:text-4xl mb-6">Daftar Sekarang</h1>
      <p class="text-white font-semibold text-sm text-center">Buat akun baru dan mulailah perjalanan Anda bersama kami!</p>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-64 md:h-80 mt-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
      </svg>
    </section>

    <section class="w-full md:w-1/2 bg-white rounded-r-2xl p-8 flex flex-col items-center">
      <h1 class="text-black font-bold text-2xl md:text-3xl mb-6">Buat Akun Baru</h1>

      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 w-full">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('prosesregisguru') }}" class="w-full space-y-4">
        @csrf
        <div class="relative">
          <label for="nama" class="text-black font-semibold text-sm mb-1">Nama</label>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <input type="text" name="nama" id="nama" required placeholder="Nama Anda"
              class="w-full py-2 pl-10 pr-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
          </div>
        </div>

        <div class="relative">
          <label for="email" class="text-black font-semibold text-sm mb-1">Email</label>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <input type="email" name="email" id="email" required placeholder="Email Anda"
              class="w-full py-2 pl-10 pr-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
          </div>
        </div>

        <div class="relative">
          <label for="password" class="text-black font-semibold text-sm mb-1">Password</label>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.1.9-2 2-2s2 .9 2 2-2 4-2 4m-4 2H5v-3a3 3 0 013-3h2m0 0c0-1.1.9-2 2-2s2 .9 2 2m-6 7a7 7 0 1114 0H5z" />
            </svg>
            <input type="password" name="password" id="passwordInput" required placeholder="Kata Sandi Anda"
              class="w-full py-2 pl-10 pr-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <button type="button" id="togglePassword" class="absolute right-3 text-gray-400 hover:text-gray-600">
              <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <div class="relative">
          <label for="no_hp" class="text-black font-semibold text-sm mb-1">No HP</label>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <input type="tel" name="no_hp" id="no_hp" required placeholder="No HP Anda"
              class="w-full py-2 pl-10 pr-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
          </div>
        </div>

        <div class="relative">
          <label for="alamat" class="text-black font-semibold text-sm mb-1">Alamat</label>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 -mt-14 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <textarea name="alamat" id="alamat" required placeholder="Alamat Anda"
              class="w-full py-2 pl-10 pr-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent h-24 resize-none"></textarea>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-teal-500 py-2 rounded-xl text-white font-bold hover:bg-teal-600 transition duration-150">
          Daftar
        </button>

        <div class="flex text-sm text-slate-600 font-semibold mt-4 justify-center">
          <p class="mr-1">Sudah punya akun?</p>
          <a href="{{ route('loginGuru') }}" class="hover:underline text-teal-500">Silahkan Masuk!</a>
        </div>
      </form>
    </section>
  </main>

  <script>
    const togglePasswordVisibility = (inputId, iconId) => {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      return () => {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.setAttribute('stroke', type === 'text' ? 'teal' : 'currentColor');
      };
    };

    document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility('passwordInput', 'eyeIcon'));
  </script>
