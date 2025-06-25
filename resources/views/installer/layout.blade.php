<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiveForge Installation Wizard</title>
    <link rel="icon" href="https://placehold.co/32x32/0d6efd/FFFFFF?text=DF">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .btn { @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors; }
        .btn-primary { @apply text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500; }
        .btn-secondary { @apply text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-slate-500; }
        .form-input { @apply block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm; }
    </style>
</head>
<body class="h-full">
<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full space-y-8 bg-white p-2 sm:p-8 rounded-2xl shadow-2xl">
        <header class="text-center">
            <img src="https://placehold.co/200x50/111827/FFFFFF?text=DiveForge" alt="DiveForge" class="mx-auto h-12 w-auto">
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900">DiveForge Installation</h1>
        </header>

        <nav class="p-4 bg-slate-50 rounded-lg">
            <ol class="flex items-center w-full">
                @php $steps = ['Welcome', 'Database', 'Admin', 'Shop', 'Finish']; @endphp
                @foreach($steps as $i => $title)
                    @php $index = $i + 1; @endphp
                    <li class="flex w-full items-center {{ !$loop->last ? 'text-blue-600 after:content-[\'\'] after:w-full after:h-1 after:border-b after:border-blue-100 after:border-2 after:inline-block' : '' }}">
                        @if(session('installer.step'.($index-1).'_complete') || $current_step >= $index)
                            <span class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full lg:h-12 lg:w-12 shrink-0">
                                @if(session('installer.step'.$index.'_complete') || ($current_step > $index))
                                    <svg class="w-5 h-5 text-blue-600 lg:w-6 lg:h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                @else
                                    <span class="font-bold text-blue-600">{{ $index }}</span>
                                @endif
                            </span>
                        @else
                             <span class="flex items-center justify-center w-10 h-10 bg-slate-100 rounded-full lg:h-12 lg:w-12 text-slate-500 shrink-0">{{ $index }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>

        <main>
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-6"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Error: {{ session('error') }}</h3></div></div></div>
            @endif
            @if($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3><div class="mt-2 text-sm text-red-700"><ul role="list" class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div></div></div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
