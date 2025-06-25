@extends('installer.layout', ['current_step' => 3])
@section('content')
<form action="{{ route('installer.step3.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Create Administrator Account</h2><p class="mt-2 text-sm text-slate-600">Set up the primary administrator for your DiveForge installation.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
      <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
          <div><label for="first_name" class="block text-sm font-medium text-slate-700">First Name</label><input type="text" name="first_name" value="{{ old('first_name') }}" required class="form-input"></div>
          <div><label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label><input type="text" name="last_name" value="{{ old('last_name') }}" required class="form-input"></div>
      </div>
      <div><label for="email" class="block text-sm font-medium text-slate-700">Email Address</label><input type="email" name="email" value="{{ old('email') }}" required class="form-input"></div>
      <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
          <div><label for="password" class="block text-sm font-medium text-slate-700">Password</label><input type="password" name="password" required class="form-input"><p class="mt-1 text-xs text-slate-500">Minimum 12 characters.</p></div>
          <div><label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label><input type="password" name="password_confirmation" required class="form-input"></div>
      </div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step2.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Next Step</button></div></div>
</form>
@endsection
