<article class="{{ $testimonial['highlighted'] ? 'border-slate-900 bg-slate-950 text-white shadow-[0_22px_46px_-26px_rgba(2,6,23,0.76)]' : 'bg-white text-slate-900 shadow-[0_16px_36px_-28px_rgba(15,23,42,0.18)] border-slate-200/80' }} rounded-[1.35rem] border p-4 sm:rounded-[1.5rem] sm:p-5">
    <p class="text-sm leading-6 {{ $testimonial['highlighted'] ? 'text-slate-100' : 'text-slate-600' }}">
        “{{ $testimonial['quote'] }}”
    </p>
    <div class="mt-4 text-center sm:mt-5">
        <h3 class="text-sm font-bold sm:text-base">{{ $testimonial['name'] }}</h3>
        <p class="mt-1 text-sm {{ $testimonial['highlighted'] ? 'text-slate-300' : 'text-slate-500' }}">{{ $testimonial['role'] }}</p>
    </div>
</article>
