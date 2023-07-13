<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { onMounted, onBeforeUnmount } from 'vue';

    const props = defineProps({
        events: {
            type: Object,
        },
    });

    let grow = 0;

    onMounted(() => {
        const eventItem = document.getElementById('eventItem0');
        const rect = eventItem.getBoundingClientRect();
        const eventItemSize = rect.bottom.toFixed() - rect.top.toFixed();

        window.onscroll = function() {scrollFunction(eventItem, eventItemSize)};
        
        eventItem.style.margin = 0;
        document.getElementById('eventItem1').style.margin = 0;
    })

    onBeforeUnmount(() => {
        window.onscroll = null;
    })

    function scrollFunction(eventItem, eventItemSize){
        // console.log("scrolling");
        const rect = eventItem.getBoundingClientRect();
        let scrollPosition = Math.floor(-rect.top.toFixed()/(eventItemSize + 48) +2.2);

        if(grow != scrollPosition){
            if(grow > scrollPosition){
                document.getElementById('eventItem' + (scrollPosition + 1)).style.margin = '15rem 2rem';
            }
            else if(scrollPosition >= props.events.length){ return; }
            grow = scrollPosition;
            document.getElementById('eventItem' + scrollPosition).style.margin = 0;
        }
    }

</script>
<template>
    <Head title="Events" />
    <PlannerLayout>
        <div v-if="$page.props.auth.user.user_group_id == 2" class="flex justify-between w-full mb-3 py-3 px-4 bg-yellow-500">
            <Link :href="route('events.create')" class="text-sm bg-white px-2 border border-gray-200 border-2 rounded">
                <svg class="inline stroke-black fill-none w-4 h-4">
                    <use href="/icons/feather-sprite.svg#plus" />
                </svg>
                 New Event
            </Link>
            <div class="text-2xl font-semibold">Your Events</div>
        </div>
        <div v-else>Events</div>
        <div class="flex flex-col gap-12 mb-12">
            <Link v-for="(event, index) in props.events" :href="route('events.show', [event.slug])">
                <div :id="'eventItem' + index" :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                    class="bg-cover bg-center mx-8 my-60 transition-[margin] duration-700">
                    <div class="bg-black/40 text-white">
                        <div class="w-full pl-6 pr-4 py-2">
                            <span class="font-bold text-2xl">{{ event.title }}</span>
                        </div>
                    </div>
                    <div class="relative py-20"></div>
                    <div class="absolute bg-white h-16 px-2 mx-4 -my-8 overflow-clip transition-[padding] duration-500">{{ event.description.substring(0, 100) }} ...</div>
                </div>
            </Link>
        </div>
    </PlannerLayout>
</template>