<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class FactoryEnglishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planner = \App\Models\UserGroup::where('name', 'Planner')->first();
        $event_planner = \App\Models\User::factory()->for($planner)->create();
        /** AI english Planner Events */
        $event_descriptions = array(
            0 => array(
                'slug' => 'group-kayaking-tour',
                'title' => 'Group Kayaking Tour',
                'description' => "Welcome to our exhilarating group kayaking tour, where adventure awaits as we paddle through the pristine waters of [destination]. Whether you're an experienced paddler or a first-time kayaker, our expert guides will ensure a memorable and safe journey. As we set off on our kayaks, the stunning natural landscapes will surround us, from majestic cliffs to lush forests teeming with wildlife. Prepare to be awe-struck as we glide through calm waters, witnessing the harmony of nature and the soothing sounds of water gently lapping against our kayaks. Our group will forge unforgettable connections as we share laughter and stories along the way. Join us for an unforgettable day of bonding with nature, making new friends, and creating memories that will last a lifetime. As the sun sets on the horizon, our group kayaking tour takes on a magical charm. Twilight casts a golden hue over the waters, and the serenity of the surroundings becomes even more profound. Our knowledgeable guides will share fascinating insights into the local ecology, history, and points of interest, enriching your experience and fostering a deeper appreciation for the natural wonders around us. Whether we encounter playful dolphins, graceful swans, or colorful fish, the wildlife encounters will be nothing short of enchanting. Feel the thrill as we navigate gentle rapids and explore hidden coves, all while building camaraderie with your fellow paddlers. Our group kayaking tour promises an extraordinary adventure that will renew your spirit and leave you with cherished memories of an extraordinary day on the water.",
                'image1' => 'images/unsplash/events/filip-mroz-zK049OFP4uI-unsplash.jpg',
                'image2' => 'images/unsplash/events/gigi-e8x9O3dehuk-unsplash.jpg',
            ),
            1 => array(
                'slug' => 'street-food-festival',
                'title' => 'Street Food Festival',
                'description' => "Indulge your taste buds in a gastronomic extravaganza at our vibrant Street Food Festival! Bringing together the rich tapestry of flavors from around the world, this culinary fiesta promises a delightful experience for all food enthusiasts. Wander through the bustling lanes lined with food stalls, where talented chefs whip up delectable dishes right before your eyes. From sizzling kebabs and mouthwatering tacos to fragrant curries and sweet treats, our diverse array of street food delights will take you on an unforgettable journey of culinary exploration. With live music, colorful decorations, and a lively atmosphere, the Street Food Festival is not just an event; it's a celebration of global cuisines, community, and the joy of sharing delicious food with friends and family. Come and join us as we celebrate the art of street food, connecting cultures through the universal language of taste.",
                'image1' => 'images/unsplash/events/al-nik-K0mrkZiTbfQ-unsplash.jpg',
                'image2' => 'images/unsplash/events/frederick-medina-HLG35jI85V8-unsplash.jpg',
            ),
            2 => array(
                'slug' => 'beer-festival',
                'title' => 'Beer Festival',
                'description' => "Welcome to the ultimate celebration of hops, barley, and pure brewing excellence - our Beer Festival! Prepare your taste buds for an extraordinary experience as we gather a wide selection of craft beers from renowned breweries and passionate artisans. Whether you're a seasoned beer connoisseur or an eager newcomer to the world of brews, our festival has something for everyone. Immerse yourself in the lively ambiance, surrounded by the aroma of freshly poured beers and the cheers of fellow enthusiasts. Sample a diverse range of beer styles, from crisp lagers and aromatic IPAs to rich stouts and refreshing ales, each one showcasing the ingenuity and creativity of master brewers. Beyond the libations, there'll be live music, engaging brewery talks, and delectable food pairings that perfectly complement the beers. Join us for a weekend of camaraderie, discovery, and a toast to the timeless art of brewing. Cheers to an unforgettable Beer Festival experience!",
                'image1' => 'images/unsplash/events/markus-spiske-i1ksCIjm0dQ-unsplash.jpg',
                'image2' => 'images/unsplash/events/markus-spiske-qhgtBITGZeI-unsplash.jpg',
            ),
            3 => array(
                'slug' => 'hiking-tour',
                'title' => 'Hiking Tour',
                'description' => "Step into the untouched beauty of nature with our invigorating Rural Hike Tour. This expedition takes you off the beaten path and into the heart of serene countryside landscapes, where rolling hills, lush meadows, and charming farmsteads await. Led by experienced guides who intimately know the region, we'll embark on a journey that showcases the raw splendor of rural life. As we traverse through scenic trails, immerse yourself in the sights and sounds of nature, from the melodious chirping of birds to the gentle rustling of leaves in the breeze. Along the way, we'll encounter welcoming locals, each with their unique stories and warm hospitality. Indulge in traditional farm-to-table meals prepared with love, savoring the flavors of the region's bounty. The Rural Hike Tour promises an escape from the hustle and bustle of city life, offering a rejuvenating experience that will leave you with a deep appreciation for the simplicity and beauty of life in the countryside.",
                'image1' => 'images/unsplash/events/ronan-furuta-wqodnf_H6zs-unsplash.jpg',
                'image2' => 'images/unsplash/events/sajad-baharvandi-tJJ8ULHTI44-unsplash.jpg',
            ),
            4 => array(
                'slug' => 'evening-music',
                'title' => 'Evening Music',
                'description' => "Get ready to groove and rock the night away at our electrifying Live Band Event! Join us for an unforgettable evening filled with pulsating rhythms, soulful melodies, and high-energy performances that will keep you on your feet all night long. Our lineup features an eclectic mix of talented bands from various genres, each bringing their unique flair to the stage. From classic rock anthems to contemporary hits, there's something for everyone to dance and sing along to. The vibrant atmosphere will be complemented by dynamic stage lighting, creating a captivating visual spectacle to match the sonic feast. So grab your friends, let loose, and create lasting memories as we celebrate the magic of live music. Don't miss this opportunity to be part of an extraordinary musical journey that will leave you with an undeniable buzz and a passion for the power of live performances. Get ready to experience the thrill of the stage and the collective energy of the crowd – this Live Band Event promises to be an unforgettable night of pure musical euphoria!",
                'image1' => 'images/unsplash/events/gama-films-LHhzTa93XH0-unsplash.jpg',
                'image2' => 'images/unsplash/events/pradeep-charles-1AJZQiJ30ag-unsplash.jpg',
            ),
            5 => array(
                'slug' => 'car-show',
                'title' => 'Car Show',
                'description' => "Rev up your engines and get ready for an automotive extravaganza at our spectacular Car Show! This event is a haven for car enthusiasts and curious onlookers alike, showcasing a mesmerizing display of automotive craftsmanship and innovation. From vintage classics to cutting-edge supercars, our curated lineup features a diverse array of vehicles that will leave you in awe. Expert car owners and collectors will be on hand to share the fascinating stories behind each automobile, giving you unique insights into the world of automotive passion. As you stroll through the gleaming rows of cars, you'll be captivated by the meticulous detailing and awe-inspiring designs that make each vehicle a true work of art. Whether you have a penchant for speed, elegance, or engineering marvels, the Car Show promises an immersive experience that will ignite your automotive passion and leave you with a newfound appreciation for the timeless allure of cars. Don't miss this opportunity to witness automotive history in motion and celebrate the craftsmanship, innovation, and beauty that define the world of automobiles.",
                'image1' => 'images/unsplash/events/alex-suprun-AvIEqv1iY-4-unsplash.jpg',
                'image2' => 'images/unsplash/events/jason-leung-4BKiS_BgOwI-unsplash.jpg',
            ),
        );

        $events = \App\Models\Event::factory(count($event_descriptions))
            ->sequence(fn ($sequence) => [
                'slug' => $event_descriptions[$sequence->index]['slug'],
                'title' => $event_descriptions[$sequence->index]['title'],
                'description'=> $event_descriptions[$sequence->index]['description'],
                'is_active' => 1,
            ])
            ->for($event_planner)
            ->create();

        foreach($events as $key => $event){
            /** Put images into storage for the according Event Id's */
            Storage::disk('event_images')->putFile($event->id, new File(public_path($event_descriptions[$key]['image1'])));
            Storage::disk('event_images')->putFile($event->id, new File(public_path($event_descriptions[$key]['image2'])));
            /** Factory Event Schedules */
            $days = collect(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'])->shuffle()->take(fake()->numberBetween(1, 6));
            \App\Models\EventSchedule::factory()
                ->for($event)
                ->count(count($days))
                ->sequence(fn ($sequence) => ['day' => $days[$sequence->index]])
                ->create();
        }
        /** END AI english Planner Events */
    }
}
