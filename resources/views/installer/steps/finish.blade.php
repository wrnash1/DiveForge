@extends('installer.layout', ['current_step' => 5])
@section('content')
<form action="{{ route('installer.finish') }}" method="POST">
    @csrf
    <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <h2 class="mt-4 text-xl font-bold text-slate-800">Ready to Install!</h2>
        <p class="mt-2 text-sm text-slate-600">Everything is configured. Click the button below to finalize the installation.</p>
    </div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step4.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary font-bold">Run Installation</button></div></div>
</form>
@endsection
