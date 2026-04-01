<article class="{{ $testimonial['highlighted'] ? 'border-slate-900 bg-slate-950 text-white shadow-[0_28px_70px_-34px_rgba(2,6,23,0.85)]' : 'bg-white text-slate-900 shadow-[0_18px_45px_-34px_rgba(15,23,42,0.28)] border-slate-200/80' }} rounded-[1.6rem] border p-7">
    <p class="text-[1.02rem] leading-8 {{ $testimonial['highlighted'] ? 'text-slate-100' : 'text-slate-600' }}">
        “{{ $testimonial['quote'] }}”
    </p>
    <div class="mt-8">
        <h3 class="text-base font-bold">{{ $testimonial['name'] }}</h3>
        <p class="mt-1 text-sm {{ $testimonial['highlighted'] ? 'text-slate-300' : 'text-slate-500' }}">{{ $testimonial['role'] }}</p>
    </div>
</article>
