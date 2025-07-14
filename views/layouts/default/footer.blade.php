<footer class="footer mt-auto">
    <div class="bg-neutral-100 py-10">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-5 gap-y-10">
                <div>
                    <h3 class="font-bold">دسترسی سریع</h3>

                    <ul class="mt-3 space-y-1 font-light">
                        <li>
                            <a href="#">صفحه اصلی</a>
                        </li>
                        <li>
                            <a href="#">جدیدترین ها</a>
                        </li>
                        <li>
                            <a href="#">پرفروش ترین ها</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold">تماس با ما</h3>

                    <div class="mt-3 space-y-1 font-light">
                        @if ($contactTelephone = settings('contact')['telephone'])
                            <div>
                                تلفن تماس: {{ $contactTelephone }}
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="font-bold">شبکه های اجتماعی</h3>

                    <div class="flex items-center space-x-5 space-x-reverse mt-3 font-light">
                        @php
                            $socialNetworks = settings('contact')['social_networks'];
                        @endphp

                        @if (isset($socialNetworks['telegram']))
                            <a href="{{ $socialNetworks['telegram'] }}" target="_blank">
                                <img src="{{ template_asset('/assets/images/icons/telegram.svg') }}" class="w-8 h-8">
                            </a>
                        @endif
                        @if (isset($socialNetworks['instagram']))
                            <a href="{{ $socialNetworks['instagram'] }}" target="_blank">
                                <img src="{{ template_asset('/assets/images/icons/instagram.svg') }}" class="w-8 h-8">
                            </a>
                        @endif
                        @if (isset($socialNetworks['facebook']))
                            <a href="{{ $socialNetworks['facebook'] }}" target="_blank">
                                <img src="{{ template_asset('/assets/images/icons/facebook.svg') }}" class="w-8 h-8">
                            </a>
                        @endif
                        @if (isset($socialNetworks['twitter']))
                            <a href="{{ $socialNetworks['twitter'] }}" target="_blank">
                                <img src="{{ template_asset('/assets/images/icons/twitter.svg') }}" class="w-6 h-6">
                            </a>
                        @endif
                        @if (isset($socialNetworks['whatsapp']))
                            <a href="{{ $socialNetworks['whatsapp'] }}" target="_blank">
                                <img src="{{ template_asset('/assets/images/icons/whatsapp.svg') }}" class="w-9 h-9">
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex justify-center md:justify-end">
                    <div class="flex items-center justify-center w-32 h-32 p-2 border border-neutral-200 rounded-md">
                        {!! settings('general')['enamad_code'] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (settings('general')['description'])
        <div class="bg-white border-t border-neutral-200 py-5">
            <div class="container">
                <h1 class="h2 text-green-500">{{ settings('general')['title'] }}</h1>
                <div class="text-sm leading-loose mt-2">
                    {!! wrap_paragraph(settings('general')['description']) !!}
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white border-t border-neutral-200 py-3">
        <div class="container flex flex-col md:flex-row items-center justify-center md:justify-between text-sm text-center">
            <div>کلیه  حقوق این سایت محفوظ است.</div>
            <a class="bg-gradient-to-l from-[#fc466b] to-[#3f5efb] badge text-sm mt-3 md:mt-0 shadow text-white hover:shadow-lg hover:text-black hover:bg-white" href="https://wester.ir" target="_blank">طراحی و اجرا توسط <b>وستر</b></a>
        </div>
    </div>
</footer>
