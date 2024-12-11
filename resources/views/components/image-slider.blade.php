<div x-data="slider()" x-init="initializeSlider()" class="relative mx-auto max-w-screen-xl" @mouseenter="pauseAutoRotation" @mouseleave="resumeAutoRotation">
    <!-- Image container -->
    <div class="overflow-hidden relative">
        <div class="flex transition-transform duration-700 ease-in-out" :style="transformStyle">
            <template x-for="(image, index) in images" :key="index">
                <img :src="'/img/image-slider/' + image" :alt="`Slide ${index + 1}`" class="w-full h-auto object-cover flex-shrink-0 rounded lazyload">
            </template>
        </div>
    </div>

    <!-- Navigation buttons -->
    <button @click="prevImage" aria-label="Previous"
        class="absolute top-1/2 left-4 transform -translate-y-1/2 p-3 bg-white text-black dark:bg-gray-800 dark:text-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <button @click="nextImage" aria-label="Next"
        class="absolute top-1/2 right-4 transform -translate-y-1/2 p-3 bg-white text-black dark:bg-gray-800 dark:text-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <!-- Indicators -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <template x-for="(image, index) in images" :key="index">
            <button
                :class="{
                    'bg-warning-500 dark:bg-warning-200': currentImage === index,
                    'bg-gray-300 dark:bg-gray-700': currentImage !== index
                }"
                @click="goToImage(index)" aria-label="Go to" class="w-3 h-3 rounded-full focus:outline-none">
            </button>
        </template>
    </div>
</div>

@push('scripts')
    <script>
        function slider() {
            return {
                images: [],
                currentImage: 0,
                intervalId: null,
                isPaused: false,

                initializeSlider() {
                    this.loadImages();
                    if (this.images.length > 1) {
                        this.shuffleImages();
                        this.startAutoRotation();
                    }
                },

                loadImages() {
                    try {
                        const allImages = @json(scandir(public_path('img/image-slider'))).filter(image => !image.startsWith('.'));
                        const allowedExtensions = ['png', 'webp', 'jpg', 'jpeg'];
                        this.images = allImages.filter(image => {
                            const extension = image.split('.').pop().toLowerCase();
                            return allowedExtensions.includes(extension);
                        });
                    } catch (error) {
                        console.error("Error loading images: ", error);
                        this.images = [];
                    }
                },

                shuffleImages() {
                    for (let i = this.images.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [this.images[i], this.images[j]] = [this.images[j], this.images[i]];
                    }
                },

                prevImage() {
                    this.currentImage = (this.currentImage === 0) ? this.images.length - 1 : this.currentImage - 1;
                    this.resetAutoRotation();
                },

                nextImage() {
                    this.currentImage = (this.currentImage === this.images.length - 1) ? 0 : this.currentImage + 1;
                    this.resetAutoRotation();
                },

                goToImage(index) {
                    this.currentImage = index;
                    this.resetAutoRotation();
                },

                startAutoRotation() {
                    this.intervalId = setInterval(() => {
                        if (!this.isPaused) {
                            this.nextImage();
                        }
                    }, 10000);
                },

                pauseAutoRotation() {
                    this.isPaused = true;
                },

                resumeAutoRotation() {
                    this.isPaused = false;
                },

                resetAutoRotation() {
                    clearInterval(this.intervalId);
                    this.startAutoRotation();
                },

                get transformStyle() {
                    return `transform: translateX(-${this.currentImage * 100}%)`;
                }
            };
        }
    </script>
@endpush
