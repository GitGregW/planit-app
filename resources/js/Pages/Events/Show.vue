<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    // import EventImage from '@/Pages/EventImages/Show.vue';
    // import EventSchedule from '@/Pages/EventSchedules/Show.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import { onMounted, onBeforeUnmount } from 'vue';

    const props = defineProps({
        event: {
            type: Object,
        },
        images: {
            type: Array,
        },
        schedules: {
            type: Object,
        },
    });

    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const d = new Date();
    const today = days[d.getUTCDay() - 1];
    if( !today ) today = 'Sunday';
    const imageCount = Object.keys(props.images).length;
    var i = 0;
    var carousel = window.setInterval(carouselImage, 8000);
    

    onMounted(() => {
        window.onscroll = function() {scrollFunction()};
    })

    onBeforeUnmount(() => {
        window.clearInterval(carousel);
        window.onscroll = null;
    })

    function timeFormatting(time){
        return new Date('1970-01-01T' + time + 'Z')
            .toLocaleTimeString('en-UK',
                {timeZone: 'UTC', hour: 'numeric', minute: 'numeric'}
            );
    }

    function scrollFunction(){
        if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
            document.getElementById("headerPadding").setAttribute('class', 'py-14 transition-[padding] duration-500');
        } else {
            document.getElementById("headerPadding").setAttribute('class', 'py-28 transition-[padding] duration-500');
        }
    }

    function selectImage(index){
        window.clearInterval(carousel);
        document.getElementById("image" + i).setAttribute('class', 'inline stroke-white fill-none stroke-2 w-8 h-8 p-2 cursor-pointer');
        i = index;
        document.getElementById("image" + index).setAttribute('class', 'inline stroke-white fill-yellow-500/50 stroke-2 w-8 h-8 p-2 cursor-pointer');
        document.getElementById("headerImage").style.backgroundImage = "url('/event_images/" + props.images[index] + "')"; 
        // document.getElementById("headerImage").style.backgroundImage = 'background-image: url(' + '\'' + '/event_images/' + props.images[index] + '\'' + ')'; 
        carousel = window.setInterval(carouselImage, 8000);
    }

    function carouselImage() {
        document.getElementById("image" + i).setAttribute('class', 'inline stroke-white fill-none stroke-2 w-8 h-8 p-2 cursor-pointer');
        i == imageCount - 1 ? i = 0 : i++;
        document.getElementById("headerImage").style.backgroundImage = "url('/event_images/" + props.images[i] + "')"; 
        document.getElementById("image" + i).setAttribute('class', 'inline stroke-white fill-yellow-500/50 stroke-2 w-8 h-8 p-2 cursor-pointer');
    }

    function scrollTop(){ window.scrollTo(0, 0); }
</script>
<template>
    <Head :title="props.event.title" />

    <PlannerLayout>
        <div class="fixed top-0 bg-gray-300">
            <div id="headerImage" :style="'background-image: url(' + '\'' + '/event_images/' + props.images[0] + '\'' + ');'" class="bg-cover bg-center w-screen">
                <div id="headerPadding" @click="scrollTop()" class="py-28 transition-[padding] duration-500"></div>
                <div class="w-screen bg-black/60 text-white">
                    <div class="flex place-content-center border-b border-white">
                        <svg v-for="(image, index) in props.images" :id="'image' + index" @click="selectImage(index)" :class="'inline stroke-white stroke-2 w-8 h-8 p-2 cursor-pointer ' + (index ? 'fill-none' : 'fill-yellow-500/50')">
                            <use href="/icons/feather-sprite.svg#circle" />
                        </svg>
                    </div>
                    <div class="flex space-between w-full pl-6 pr-4 py-2">
                        <span class="font-bold text-2xl">{{ $props.event.title }}</span>
                        <Link :href="route('event_bookings.create', $props.event.slug)" class="border-2 border-yellow-400 round-2xl py-1 px-2 ml-auto hover:bg-white/40"
                            as="button" type="button">Book</Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative mt-80">
            <div v-if="$page.props.auth.user.user_group_id == 2" class="absolute top-2">
                <Link :href="route('events.edit', [props.event.slug])" class="text-md bg-white pl-2 pr-4 py-1 border border-orange-600 border-2 rounded ml-4">
                    <svg class="inline stroke-black fill-none w-4 h-4">
                        <use href="/icons/feather-sprite.svg#edit-2" />
                    </svg>
                    Edit
                </Link>
            </div>
            <div class="text-right text-sm text-gray-700 bg-white py-2 px-3">
                <span>Today</span>
                <svg class="inline stroke-gray-700 fill-none stroke-2 w-4 h-4 ml-1 mr-2">
                    <use href="/icons/feather-sprite.svg#clock" />
                </svg>
                <span v-if="!props.schedules[today]">Closed.</span>
                <span v-else-if="props.schedules[today].opening_time > d.toLocaleTimeString()">Opens from {{ timeFormatting($props.schedules[today].opening_time) }}</span>
                <span v-else>Open until {{ timeFormatting($props.schedules[today].closing_time) }}</span>
            </div>
            <div class="w-screen pt-2 pb-4 pl-4 pr-2 bg-white justify-center">
                <p>{{ $props.event.description }}</p>
            </div>
            <div class="grid grid-cols-7 border-y-2 border-yellow-600">
                <p class="col-span-7 text-gray-700 text-sm text-semibold pl-3 py-1 bg-yellow-400">
                    Distance
                    <svg class="inline stroke-gray-700 fill-none stroke-2 w-4 h-4">
                        <use href="/icons/feather-sprite.svg#compass" />
                    </svg>
                    0.0 miles from [SEARCH POSTCODE]
                </p>
                <img src="/images/unsplash/planit/z-TrhLCn1abMU-unsplash.jpg" class="object-fill h-full w-full col-span-3" />
                <ul class="py-2 px-4 decoration-none col-span-4 bg-white">
                    <li>{{ $props.event.address_line_1 }}</li>
                    <li>{{ $props.event.address_line_2 }}</li>
                    <li>{{ $props.event.city }}</li>
                    <li>{{ $props.event.county }}</li>
                    <li>{{ $props.event.postcode }}</li>
                    <li>{{ $props.event.landline }}</li>
                    <li>{{ $props.event.mobile }}</li>
                </ul>
            </div>
            <ul class="text-sm w-fit text-right round-xl my-6 mx-auto divide-y-4 divide-gray-200 rounded-sm">
                <li v-for="day in days" :class="'grid grid-cols-3 gap-3 px-2 py-1 bg-white ' + (today == day ? 'border-l-8 border-yellow-500' : '')">
                    <div class="font-semibold text-md">{{ day }}</div>
                    <div v-if="schedules[day]" class="col-span-2">
                        <span>{{ timeFormatting($props.schedules[day].opening_time) }}</span>
                        <span> - </span>
                        <span>{{ timeFormatting($props.schedules[day].closing_time) }}</span>
                    </div>
                </li>
                <!-- List for custom dates here. -->
            </ul>
        </div>
    </PlannerLayout>
</template>