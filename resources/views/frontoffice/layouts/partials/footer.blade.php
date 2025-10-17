<footer id="footer" class="py-28">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-between">
            <div class="w-full lg:w-1/4 sm:w-1/2 pb-6 lg:pb-0">
                <div>
                    <img src="{{ asset('template/images/main-logo.png') }}" alt="logo"
                        class="w-auto h-auto mb-2 max-w-full">
                    <p class="mb-4">Nisi, purus vitae, ultrices nunc. Sit ac sit suscipit hendrerit. Gravida massa
                        volutpat
                        aenean odio erat nullam fringilla.</p>
                    <div class="social-links">
                        <ul class="flex space-x-4 list-none">
                            <li>
                                <a href="#">
                                    <svg class="facebook w-6 h-6 text-gray-400 hover:text-primary">
                                        <use xlink:href="#facebook" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="instagram w-6 h-6 text-gray-400 hover:text-primary">
                                        <use xlink:href="#instagram" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="twitter w-6 h-6 text-gray-400 hover:text-primary">
                                        <use xlink:href="#twitter" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="linkedin w-6 h-6 text-gray-400 hover:text-primary">
                                        <use xlink:href="#linkedin" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <svg class="youtube w-6 h-6 text-gray-400 hover:text-primary">
                                        <use xlink:href="#youtube" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/6 sm:w-1/2 pb-6 lg:pb-0">
                <div>
                    <h5 class="font-bold pb-2">Quick Links</h5>
                    <ul class="list-none capitalize">
                        <li class="mb-1">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('book') }}">Book</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('notes') }}">Notes</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('groups.index') }}">Groups</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('marketplace') }}">Marketplace</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('blog') }}">Blog</a>
                        </li>
                        <li class="mb-1">
                            <a href="{{ route('community') }}">Community Features</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full lg:w-1/4 sm:w-1/2 pb-6 lg:pb-0">
                <div>
                    <h5 class="font-bold pb-2 capitalize">Help & Info Help</h5>
                    <ul class="list-none">
                        <li class="mb-1">
                            <a href="#">Track Your Order</a>
                        </li>
                        <li class="mb-1">
                            <a href="#">Returns Policies</a>
                        </li>
                        <li class="mb-1">
                            <a href="#">Shipping + Delivery</a>
                        </li>
                        <li class="mb-1">
                            <a href="#">Contact Us</a>
                        </li>
                        <li class="mb-1">
                            <a href="#">Faqs</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full lg:w-1/4 sm:w-1/2 pb-6 lg:pb-0">
                <div>
                    <h5 class="font-bold pb-2 capitalize">Contact Us</h5>
                    <p class="mb-2">Do you have any queries or suggestions? <a href="mailto:contact@bookly.com"
                            class="underline">contact@bookly.com</a></p>
                    <p>If you need support? Just give us a call. <a href="tel:+551112223344" class="underline">+55 111
                            222 333 44</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<hr class="my-0">
<div id="footer-bottom" class="my-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex flex-wrap gap-8">
                <div class="flex items-center">
                    <p>We ship with:</p>
                    <div class="flex pl-2">
                        <img src="{{ asset('template/images/dhl.png') }}" alt="DHL" class="mr-2">
                        <img src="{{ asset('template/images/shippingcard.png') }}" alt="Shipping">
                    </div>
                </div>
                <div class="flex items-center">
                    <p>Payment options:</p>
                    <div class="flex pl-2">
                        <img src="{{ asset('template/images/visa.jpg') }}" alt="Visa" class="mr-2">
                        <img src="{{ asset('template/images/mastercard.jpg') }}" alt="Mastercard" class="mr-2">
                        <img src="{{ asset('template/images/paypal.jpg') }}" alt="PayPal">
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>© Copyright {{ date('Y') }} Bookly. Made with Laravel.</p>
            </div>
        </div>
    </div>
</div>