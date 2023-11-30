<script setup>
    import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js';
    import { Link, router, usePage } from '@inertiajs/vue3';
    import { reactive, ref, watch } from 'vue';

    const props = defineProps({
        events: {
            type: Object,
            required: true,
        }
    });

    const eventFilters = reactive({});
    const eventSort = reactive({'value': 'asc'});
    const initialUrl = usePage().url;
    var title = "";

    watch([eventFilters, () => eventSort.value], ([filters, sortBy]) => {
        router.reload({
            only: ['events'],
            data: manageData(filters, sortBy),
            onSuccess: () => {
                window.history.replaceState({}, '', initialUrl);
                reset();
            }
        });
    });

    function manageData(filters, sortBy){
        const res = {};
        for(let filter in filters){
            if(filters[filter]){
                res[filter] = filters[filter];
            }
        }
        title = Object.keys(res).length ? Object.keys(res)[0] + " " : "";
        res['sort'] = sortBy;
        res['page'] = undefined;
        return res;
    }
    
    const landmark = ref(null);
    const { items, reset, canLoadMoreItems } = useInfiniteScroll('events', landmark);
    const scrollTop = (() => window.scrollTo(0,0));
</script>

<template>
    <div class="pb-3 md:mx-6">
        
        <div class="flex justify-between w-full pt-2 bg-gray-100">
            <div class="basis-3/5 my-auto p-1 pl-3 pr-6 text-xl font-custom capitalize bg-white rounded-r">
                <span>{{ title }}Events</span>
            </div>
            <div class="flex flex-row pl-1 pr-3 px-4 text-center gap-x-2 px-2 py-1 my-auto mr-2 bg-white rounded border border-gray-200">
                <svg class="inline stroke-black fill-none w-6 h-6">
                    <use href="/icons/feather-sprite.svg#sliders" />
                </svg>
                <span>Filters</span>
                <span class="text-gray-700">({{ Object.keys(eventFilters).length }})</span>
            </div>
        </div>

        <form
            class="flex flex-row flex-wrap gap-4 justify-between px-2 pt-1 pb-2 bg-gray-100"
        >
            <div class="grid grid-cols-3">
                <input type="checkbox" v-model="eventFilters.active" id="active" class="ml-auto my-auto" />
                <label for="active" class="col-span-2 pl-2 my-auto">Active</label>
                <input type="checkbox" v-model="eventFilters.inactive" id="inactive" class="ml-auto my-auto" />
                <label for="inactive" class="col-span-2 pl-2 my-auto">Inactive</label>
            </div>
            <div class="my-auto ml-auto">
                <select v-model="eventSort.value" name="sort" id="sort">
                    <option value="asc">Sort By: Newest</option>
                    <option value="desc">Sort By: Oldest</option>
                </select>
            </div>
        </form>
        <div class="text-gray-700 text-center">{{ props.events ? props.events.total : 0 }} Events</div>

        <div class="grid sm:grid-cols-2 gap-1 p-1 mx-2 md:gap-3 lg:grid-cols-3 xl:grid-cols-4">
            <Link
                v-for="(event, index) in items"
                :href="route('events.show', [event.slug])"
                class="relative block"
            >
                <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                        class="bg-cover bg-bottom h-60 w-full md:h-72"
                ></div>
                <h1 class="absolute bottom-2 left-2 text-white text-2xl font-bold w-40 overflow-hidden">
                    {{ event.title.toUpperCase() }}
                </h1>
            </Link>

            <Link v-if="!canLoadMoreItems && (props.events ? props.events.total > 9 : false)" @click="scrollTop">Scroll back to top</Link>
            <div ref="landmark"></div>
        </div>
    </div>
</template>