import { useIntersect } from '@/Composables/useIntersect.js';
import { ref, computed } from 'vue';
import { usePage, router } from "@inertiajs/vue3";

export function useInfiniteScroll(propName, landmark = null, landmarkOptions = {rootMargin: '0px 0px 56px 0px'})
{
    const value = () => usePage().props[propName];
    const items = ref(value().data);
    const initialUrl = usePage().url;
    const canLoadMoreItems = computed(() => value().next_page_url !== null);
    const loadMoreItems = () => {
        if(!canLoadMoreItems.value){
            return;
        }
        router.get(value().next_page_url, {}, {
            preserveState: true,
            preserveScroll: true,
            
            onSuccess: () => {
                window.history.replaceState({}, '', initialUrl)
                items.value = [...items.value, ...value().data]
            }
        });
    };

    if(landmark !== null){
        useIntersect(landmark, loadMoreItems, landmarkOptions);
    }

    return {
        items,
        loadMoreItems,
        reset: () => items.value = value().data,
        canLoadMoreItems,
    }
}