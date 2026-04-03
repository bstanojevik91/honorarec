<?php

namespace App\Support;

use App\Models\BlogPost;
use Illuminate\Support\Carbon;

class DefaultBlogPosts
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function frontend(): array
    {
        return [
            [
                'slug' => 'kako-pobrzo-da-najdes-rabota-na-dnevnica',
                'title' => 'Како побрзо да најдеш работа на дневница',
                'excerpt' => 'Неколку практични совети како да го средиш профилот и да аплицираш попаметно за краткорочни ангажмани.',
                'meta_description' => 'Практични совети како побрзо да најдеш работа на дневница, како да аплицираш и што работодавачите најмногу забележуваат.',
                'category' => 'Совети за кандидати',
                'reading_time' => '4 минути читање',
                'published_at' => '01 април 2026',
                'image' => 'https://images.pexels.com/photos/16647493/pexels-photo-16647493.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'intro' => 'Брзата работа на дневница најчесто ја добиваат оние што имаат јасна порака, точен телефон и кратка, но уверлива апликација.',
                'sections' => [
                    [
                        'heading' => 'Подготви кратко, но јасно претставување',
                        'content' => 'Кога аплицираш за краткорочен ангажман, работодавачот сака веднаш да разбере кој си, дали си достапен и дали можеш брзо да започнеш. Наместо долги објаснувања, користи неколку директни реченици.',
                        'points' => [
                            'Наведи кога можеш да започнеш.',
                            'Остави точен и активен телефонски број.',
                            'Нагласи ако веќе имаш слично искуство.',
                        ],
                    ],
                    [
                        'heading' => 'Следи огласи редовно',
                        'content' => 'Огласите за дневница често се затвораат брзо. Затоа е важно да ја проверуваш платформата редовно и да аплицираш веднаш штом ќе видиш оглас што ти одговара.',
                    ],
                    [
                        'heading' => 'Телефонот нека ти биде достапен',
                        'content' => 'Голем дел од компаниите прво контактираат по телефон. Ако бројот не е точен или не одговараш навреме, шансата лесно оди кај друг кандидат.',
                    ],
                ],
            ],
            [
                'slug' => 'koi-sezonski-raboti-se-najbarani-vo-momentov',
                'title' => 'Кои сезонски работи се најбарани во моментов',
                'excerpt' => 'Погледни ги најчестите категории на работа и што работодавците најмногу бараат од кандидатите.',
                'meta_description' => 'Преглед на најбараните сезонски работи во Македонија и категориите во кои компаниите најчесто бараат кандидати.',
                'category' => 'Пазар на труд',
                'reading_time' => '3 минути читање',
                'published_at' => '30 март 2026',
                'image' => 'https://images.pexels.com/photos/4481260/pexels-photo-4481260.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'intro' => 'Сезонските ангажмани се меѓу најбараните огласи, особено во логистика, продажба, угостителство и промоции.',
                'sections' => [
                    [
                        'heading' => 'Магацин и логистика',
                        'content' => 'Во периоди на зголемена испорака и сезонски набавки, компаниите најчесто бараат магационери, сортирачи и помошни работници за товар и истовар.',
                    ],
                    [
                        'heading' => 'Угостителство и туризам',
                        'content' => 'Во туристичките места најчесто се отвораат позиции за помошен персонал, келнери, шанкери и сезонски работници во хотели и ресторани.',
                    ],
                    [
                        'heading' => 'Промоции и продажба',
                        'content' => 'Промотери, демонстратори и лица за теренска продажба се бараат кога брендови имаат активни кампањи и настани.',
                        'points' => [
                            'Комуникативност и директен контакт со луѓе.',
                            'Подготвеност за работа на терен или настани.',
                            'Флексибилност за викенд или попладневни смени.',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'kako-da-odberes-vtora-rabota-sto-ti-odgovara',
                'title' => 'Како да одбереш втора работа што ти одговара',
                'excerpt' => 'Втората работа треба да биде флексибилна и одржлива. Овој водич ќе ти помогне да процениш што ти одговара.',
                'meta_description' => 'Совети како да избереш втора работа што навистина одговара на твоето време, распоред и финансиски цели.',
                'category' => 'Кариера',
                'reading_time' => '5 минути читање',
                'published_at' => '28 март 2026',
                'image' => 'https://images.pexels.com/photos/34029802/pexels-photo-34029802.jpeg?auto=compress&cs=tinysrgb&w=1400',
                'intro' => 'Добрата втора работа не е само дополнителен приход, туку и ангажман што можеш реално да го вклопиш во секојдневието без да се преоптовариш.',
                'sections' => [
                    [
                        'heading' => 'Провери колку време навистина имаш',
                        'content' => 'Најчестата грешка е да се прифати ангажман што изгледа добро на хартија, но не е реален во однос на распоредот. Прво процени ги деновите, часовите и патувањето.',
                    ],
                    [
                        'heading' => 'Избери тип на ангажман што ти одговара',
                        'content' => 'Ако ти треба флексибилност, разгледај викенд или дневни ангажмани. Ако бараш постабилен дополнителен приход, барај подолгорочни и повторливи позиции.',
                        'points' => [
                            'За повеќе слобода избери викенд или дневни задачи.',
                            'За стабилност барај повторливи ангажмани.',
                            'Секогаш спореди ја заработката со времето што го вложуваш.',
                        ],
                    ],
                    [
                        'heading' => 'Гледај ја комуникацијата со работодавачот',
                        'content' => 'Јасна комуникација, точни услови и навремен одговор често кажуваат многу за тоа како ќе изгледа целата соработка.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function database(): array
    {
        return collect(self::frontend())
            ->map(function (array $post): array {
                return [
                    'slug' => $post['slug'],
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => self::buildContent($post),
                    'featured_image' => $post['image'],
                    'category' => $post['category'],
                    'status' => BlogPost::STATUS_PUBLISHED,
                    'published_at' => self::publishedAtForSlug($post['slug']),
                    'meta_title' => $post['title'],
                    'meta_description' => $post['meta_description'],
                ];
            })
            ->all();
    }

    private static function buildContent(array $post): string
    {
        $html = '<p>' . e($post['intro']) . '</p>';

        foreach ($post['sections'] as $section) {
            $html .= '<h2>' . e($section['heading']) . '</h2>';
            $html .= '<p>' . e($section['content']) . '</p>';

            if (!empty($section['points'])) {
                $html .= '<ul>';

                foreach ($section['points'] as $point) {
                    $html .= '<li>' . e($point) . '</li>';
                }

                $html .= '</ul>';
            }
        }

        return $html;
    }

    private static function publishedAtForSlug(string $slug): Carbon
    {
        return match ($slug) {
            'kako-pobrzo-da-najdes-rabota-na-dnevnica' => Carbon::create(2026, 4, 1, 9, 0, 0),
            'koi-sezonski-raboti-se-najbarani-vo-momentov' => Carbon::create(2026, 3, 30, 9, 0, 0),
            'kako-da-odberes-vtora-rabota-sto-ti-odgovara' => Carbon::create(2026, 3, 28, 9, 0, 0),
            default => now(),
        };
    }
}
