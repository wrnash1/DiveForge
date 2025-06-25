@extends('installer.layout', ['current_step' => 2])
@section('content')
<form action="{{ route('installer.step2.process') }}" method="POST" class="space-y-8">
    @csrf
    <div class="text-center"><h2 class="text-xl font-bold text-slate-800">Database Configuration</h2><p class="mt-2 text-sm text-slate-600">Provide your database connection details.</p></div>
    <div class="p-6 border bg-slate-50/50 border-slate-200 rounded-lg"><div class="space-y-6">
        <div class="form-group"><label for="db_connection" class="block text-sm font-medium text-slate-700">Database Type</label><select id="db_connection" name="db_connection" class="form-input"><option value="mysql" @if(old('db_connection') == 'mysql') selected @endif>MySQL / MariaDB</option><option value="pgsql" @if(old('db_connection') == 'pgsql') selected @endif>PostgreSQL</option><option value="sqlite" @if(old('db_connection') == 'sqlite') selected @endif>SQLite</option></select></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"><div class="sm:col-span-4"><label for="db_host" class="block text-sm font-medium text-slate-700">Database Host</label><input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required class="form-input"></div><div class="sm:col-span-2"><label for="db_port" class="block text-sm font-medium text-slate-700">Port</label><input type="number" id="db_port" name="db_port" value="{{ old('db_port', 3306) }}" required class="form-input"></div></div>
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"><div class="sm:col-span-3"><label for="db_database" class="block text-sm font-medium text-slate-700">Database Name</label><input type="text" id="db_database" name="db_database" value="{{ old('db_database', 'diveforge') }}" required class="form-input"></div><div class="sm:col-span-3"><label for="db_username" class="block text-sm font-medium text-slate-700">Username</label><input type="text" id="db_username" name="db_username" value="{{ old('db_username', 'root') }}" required class="form-input"></div></div>
        <div><label for="db_password" class="block text-sm font-medium text-slate-700">Password</label><input type="password" id="db_password" name="db_password" class="form-input"></div>
    </div></div>
    <div class="mt-8 pt-5 border-t border-slate-200"><div class="flex justify-between"><a href="{{ route('installer.step1.show') }}" class="btn btn-secondary">Previous</a><button type="submit" class="btn btn-primary">Test & Continue</button></div></div>
</form>
@endsection
