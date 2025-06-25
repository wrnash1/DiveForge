@extends('installer.layout', ['current_step' => 4])
@section('content')
<form action="{{ route('installer.step4.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Configure Your Dive Shop</h2><p class="mt-2 text-sm text-slate-600">Set up your dive shop's basic details.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
        <div><label for="shop_name" class="block text-sm font-medium text-slate-700">Shop Name</label><input type="text" name="shop_name" value="{{ old('shop_name', 'My Dive Shop') }}" required class="form-input"></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <div><label for="shop_email" class="block text-sm font-medium text-slate-700">Business Email</label><input type="email" name="shop_email" value="{{ old('shop_email') }}" required class="form-input"></div>
            <div><label for="shop_phone" class="block text-sm font-medium text-slate-700">Business Phone</label><input type="tel" name="shop_phone" value="{{ old('shop_phone') }}" class="form-input"></div>
        </div>
        <div><label for="shop_address" class="block text-sm font-medium text-slate-700">Business Address</label><textarea name="shop_address" rows="3" class="form-input">{{ old('shop_address') }}</textarea></div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step3.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Next Step</button></div></div>
</form>
@endsection
