@php
    $images = collect(File::files(public_path('img/image-slider')))
        ->filter(fn($file) => in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'webp']))
        ->map(
            fn($file) => [
                'imgSrc' => asset('img/image-slider/' . $file->getFilename()),
                'imgAlt' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
            ],
        )
        ->toArray();
@endphp

<div x-data="sliderComponent({{ json_encode($images) }})" x-init="init()" class="relative w-full overflow-hidden">
    <!-- Previous Button -->
    <button type="button"
        class="absolute left-2 sm:left-5 top-1/2 z-20 flex items-center justify-center p-2 bg-white/40 rounded-full -translate-y-1/2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white"
        aria-label="Previous Slide" x-on:click="previous()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="w-5 h-5 sm:w-6 sm:h-6 pr-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>

    <!-- Next Button -->
    <button type="button"
        class="absolute right-2 sm:right-5 top-1/2 z-20 flex items-center justify-center p-2 bg-white/40 rounded-full -translate-y-1/2 text-neutral-600 transition hover:bg-white/60 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:bg-neutral-950/40 dark:text-neutral-300 dark:hover:bg-neutral-950/60 dark:focus-visible:outline-white"
        aria-label="Next Slide" x-on:click="next()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="w-5 h-5 sm:w-6 sm:h-6 pl-0.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </button>

    <!-- Slides -->
    <div class="relative w-full aspect-[16/9]">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                <img class="w-full h-full object-cover rounded" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
            </div>
        </template>
    </div>

    <!-- Indicators -->
    <div class="absolute bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2 sm:gap-3 bg-white/75 px-2 py-1.5 rounded dark:bg-neutral-950/75">
        <template x-for="(slide, index) in slides" :key="index">
            <button class="w-3 h-3 sm:w-4 sm:h-4 cursor-pointer rounded-full transition" x-on:click="currentSlideIndex = index + 1"
                x-bind:class="[currentSlideIndex === index + 1 ? 'bg-neutral-600 dark:bg-neutral-300' : 'bg-neutral-600/50 dark:bg-neutral-300/50']"
                x-bind:aria-label="'Go to Slide ' + (index + 1)"></button>
        </template>
    </div>
</div>

@push('scripts')
    <script>
        function sliderComponent(images) {
            return {
                slides: images,
                currentSlideIndex: 1,
                init() {
                    setInterval(() => {
                        this.next();
                    }, 20000); // Rotate every 20 seconds
                },
                previous() {
                    this.currentSlideIndex = this.currentSlideIndex > 1 ? this.currentSlideIndex - 1 : this.slides.length;
                },
                next() {
                    this.currentSlideIndex = this.currentSlideIndex < this.slides.length ? this.currentSlideIndex + 1 : 1;
                },
            };
        }
    </script>
@endpush
