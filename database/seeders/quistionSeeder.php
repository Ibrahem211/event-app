<?php

namespace Database\Seeders;

use App\Models\quistion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class quistionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
                    [
                        'question' => 'hello',
                        'answer' =>' hello my friend , how can i help you ?',
                        'topic' => 'hello',
                        'tags' => 'hello, hi, good morning ,good afternoon , good evening'
                    ],
                    [
                        'question' => 'how are you?',
                        'answer' =>' good thanks for asking , how can i help you ?',
                        'topic' => 'hou are you',
                        'tags' => 'how are you , how are you doing'
                    ],
                    [
                        'question' => 'l want to ask you a question',
                        'answer' =>' tell how can i help you?
                        you can write your quistion directly',
                        'topic' => 'question',
                        'tags' => 'question, help , advice '
                    ],
                    [
                        'question' => 'What are the best foods for a wedding party?',
                        'answer' => 'You can offer a variety of foods like main meal like ouzi , drinks like orange frish, and desserts like cake pieces ,also you cane
                        look for the types of food that we offer by write (food) in the search box.',
                        'topic' => 'Weddings',
                        'tags' => 'food, appetizers, desserts, meals'
                    ],
                    [
                        'question' => 'What is the ideal decoration for a kids party?',
                        'answer' => 'You can use colorful balloons, cartoon character decorations,some beautiful light effects,and fun games , also you can see the
                        decorations we offer by look for it in the search box.',
                        'topic' => 'Kids Parties',
                        'tags' => 'decorations, entertainment, games'
                    ],
                    [
                        'question' => 'What are some good places for a family gathering?',
                        'answer' => 'You can consider parks, family restaurants, or community halls,write (ristaurant) in search box and chose
                        from the restaurants we have .',
                        'topic' => 'Family Gatherings , family parties',
                        'tags' => 'places, family, gathering'
                    ],
                    [
                        'question' => 'What kind of music is suitable for a graduation party?',
                        'answer' => 'You can play upbeat and celebratory music to keep the energy high, we have a DJ service.',
                        'topic' => 'Graduation Parties',
                        'tags' => 'music, celebration, upbeat'
                    ],
                    [
                        'question' => 'What are the best places for a birthday party?',
                        'answer' => 'there are many type of places suitable for a birthday party :
                        You can host it at home and chose some great services we offer to make your party wanderfull,
                        you can rent a party hall , or have it in a park ore a restaurant .
                        search the places we have after you decide the type of place you want and chose the one you like the most .',
                        'topic' => 'Birthdays',
                        'tags' => 'places, birthday, celebration'
                    ],
                    [
                        'question' => 'What kind of food can I get for a certain amount of money for a party?',
                        'answer' => 'Depending on your budget, you can get appetizers, main courses, or desserts. It\'s best to contact local caterers for exact pricing.',
                        'topic' => 'Party Food',
                        'tags' => 'food, budget, catering'
                    ],
                    [
                        'question' => 'What places can I book with a certain amount of money?',
                        'answer' => 'Your options will vary based on your budget. there are some choices :
                          you can buy some coins if you want more diversity .',
                        'topic' => 'Price',
                        'tags' => 'places, booking, budget,price'
                    ],
                    [
                        'question' => 'What decorations should I use for a wedding?',
                        'answer' => 'Consider using elegant flowers such as tulip , candles, and beautiful table settings.

        Shahed Abo Saeed, [8/11/2024 3:08 PM]
        you can check out the decoration we offer',
                        'topic' => 'Weddings',
                        'tags' => 'decorations, flowers, candles'
                    ],
                    [
                        'question' => 'What type of music is suitable for a wedding?',
                        'answer' => 'You can play romantic and classical music to set the mood.
                        also we have a DJ service if you want a noisy and enthusiastic widding !',
                        'topic' => 'Weddings',
                        'tags' => 'music, romantic, classical'
                    ],
                    [
                        'question' => 'What are some budget-friendly food options for a kids party?',
                        'answer' => 'You can serve finger foods, sandwiches,
                        and desserts with a cute design like a cake with a cream drawings.',
                        'topic' => 'Kids Parties',
                        'tags' => 'food, budget-friendly, homemade'
                    ],
                    [
                        'question' => 'How can I entertain guests at a kids party?',
                        'answer' => 'You can organize games, hire a clown, or have a magic show.',
                        'topic' => 'Kids Parties',
                        'tags' => 'entertainment, games, magic show'
                    ],
                    [
                        'question' => 'What are some popular decorations for a family gathering?',
                        'answer' => 'You can use family photos, themed tablecloths, and string lights .',
                        'topic' => 'Family Gatherings',
                        'tags' => 'decorations, photos, string lights'
                    ],
                    [
                        'question' => 'What are some popular decorations for a family gathering?',
                        'answer' => 'You can use family photos, themed tablecloths, and string lights .',
                        'topic' => 'Family parties',
                        'tags' => 'decorations, photos, string lightsm parties'
                    ],
                    [
                        'question' => 'What are some popular decorations for a family gathering?',
                        'answer' => 'You can use family photos, themed tablecloths, and string lights .',
                        'topic' => 'Family parties',
                        'tags' => 'decorations, photos, string lightsm parties'
                    ],
                    [
                        'question' => 'What are the best foods for a family gathering?',
                        'answer' => 'You can serve a buffet with various dishes that cater to all ages.
                        we offer main meals and many types of desserts ,you can check there out on search box',
                        'topic' => 'Family party',
                        'tags' => 'food, buffet, various dishes, meal, meals '
                    ],
                    [
                        'question' => 'What are the best foods for a family gathering?',
                        'answer' => 'You can serve a buffet with various dishes that cater to all ages.
                        we offer main meals and many types of desserts ,you can check there out on search box',
                        'topic' => 'Family gathering',
                        'tags' => 'food, buffet, various dishes, meal, meals '
                    ],
                    [
                        'question' => 'What type of music should I play at a family gathering?',
                        'answer' => 'Play a mix of oldies, contemporary hits, and family favorites.
                        and you can check out our services search about music in search box ',
                        'topic' => 'Family Gatherings',
                        'tags' => 'music, oldies, contemporary, family favorites'
                    ],
                    [
                        'question' => 'What type of music should I play at a family gathering?',
                        'answer' => 'Play a mix of oldies, contemporary hits, and family favorites.
                        and you can check out our services search about music in search box ',
                        'topic' => 'Family party',
                        'tags' => 'music, oldies, contemporary, family favorites, family party'
                    ],
                    [
                        'question' => 'What decorations should I use for a graduation party?',

        'answer' => 'Use graduation caps, banners, and school colors for decorations, also ballons can be used.
                        these are some of choises from alot we offer , look for decoration in search box',
                        'topic' => 'Graduation Party',
                        'tags' => 'decorations, graduation caps, banners, school colors, decoration'
                    ],
                    [
                        'question' => 'What food should I serve at a graduation party?',
                        'answer' => 'Serve finger foods, snacks, and a celebratory cake.',
                        'topic' => 'Graduation Party',
                        'tags' => 'food, finger foods, cake'
                    ],
                    [
                        'question' => 'What are the best places for a graduation party?',
                        'answer' => 'You can host it at a banquet hall, a restaurant you can check out our restaurant,
                        or even at home and chois from our services some things can make your party more beautiful.',
                        'topic' => 'Graduation Parties',
                        'tags' => 'places, banquet hall, restaurant, home'
                    ],
                    [
                        'question' => 'What decorations should I use for a birthday party?',
                        'answer' => 'Use balloons, banners, and themed tableware.
                        lights can give the party a special touch if you chose it right',
                        'topic' => 'Birthdays',
                        'tags' => 'decorations, balloons, banners, themed tableware'
                    ],
                    [
                        'question' => 'What food should I serve at a birthday party?',
                        'answer' => 'Serve a variety of snacks, main dishes, and a birthday cake.
                        we have many type of desserts in our service ,
                        you can serve a big meal too if you want , crespy is a good choise!',
                        'topic' => 'Birthdays',
                        'tags' => 'food, snacks, main dishes, birthday cake'
                    ],
                    [
                        'question' => 'What type of music is suitable for a birthday party?',
                        'answer' => 'Play upbeat and fun music to keep the party lively.
                        you can make a big party and chose our DJ ',
                        'topic' => 'Birthdays',
                        'tags' => 'music, upbeat, fun'
                    ]
                ];

                foreach ($faqs as $faq) {
                    quistion::create($faq);
                }
    }
}
