@extends('installer.layout', ['current_step' => 1])
@section('content')
<form action="{{ route('installer.step1.process') }}" method="POST">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Welcome to DiveForge</h2><p class="mt-2 text-sm text-slate-600">The Universal Open Source Dive Shop Management Platform.</p></div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🌊</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Universal Agency Support</p><p class="text-sm text-slate-500">PADI, SSI, TDI, NAUI, and more.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🔓</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Open Source Freedom</p><p class="text-sm text-slate-500">GPL v3 licensed for community ownership.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🏢</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Enterprise Ready</p><p class="text-sm text-slate-500">PCI DSS compliant with robust security.</p></div></div>
        <div class="flex items-start space-x-4 rounded-lg bg-white p-4 border border-slate-200 shadow-sm"><div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100 text-blue-600">🔄</span></div><div class="flex-1 space-y-1"><p class="text-sm font-medium text-slate-900">Easy Migration</p><p class="text-sm text-slate-500">Transition from existing systems.</p></div></div>
    </div>
    <div class="mt-8 p-6 bg-slate-50/50 border border-slate-200 rounded-lg">
        <h3 class="text-base font-semibold leading-6 text-slate-900">License Agreement</h3>
        <p class="mt-2 text-sm text-slate-600">DiveForge is licensed under the GNU General Public License v3.0 (GPL v3). By proceeding, you agree to its terms.</p>
        <fieldset class="mt-4"><div class="space-y-4"><div class="flex items-start"><div class="flex h-6 items-center"><input id="license-accepted" name="license_accepted" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"></div><div class="ml-3 text-sm leading-6"><label for="license-accepted" class="font-medium text-slate-900">I accept the GPL v3 license terms.</label></div></div></div></fieldset>
    </div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-end"><button type="submit" class="btn btn-primary">Next Step<svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" /></svg></button></div></div>
</form>
@endsection
