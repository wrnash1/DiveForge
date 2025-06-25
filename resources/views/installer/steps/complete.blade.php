@extends('installer.layout', ['current_step' => 5])
@section('content')
<div class="text-center">
    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Installation Complete!</h2>
    <p class="mt-2 text-base text-gray-500">DiveForge has been successfully installed. Your shop is ready to go.</p>
    <p class="mt-4 text-xs text-gray-400">For security, the installer has now been disabled.</p>
    <div class="mt-6"><a href="/" class="btn btn-primary text-base font-bold">Go to Your Dashboard &rarr;</a></div>
</div>
@endsection
