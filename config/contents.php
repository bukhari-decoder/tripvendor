<?php
return [
    'hero_one' => [
        'single' => [
            'field_name' => [
                'search_topic_one' => 'text',
                'search_topic_two' => 'text',
                'search_topic_three' => 'text',
                'search_topic_four' => 'text',
                'search_button' => 'text',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',
                'image_four' => 'file',
            ],
            'validation' => [
                'search_topic_one.*' => 'required|max:300',
                'search_topic_two.*' => 'required|max:300',
                'search_topic_three.*' => 'required|max:300',
                'search_topic_four.*' => 'required|max:300',
                'search_button.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_three.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_four.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_part_one' => 'text',
                'sub_title_part_two' => 'text',
                'sub_title_part_three' => 'text',
                'description' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'name.*' => 'required|max:300',
                'sub_title_part_one.*' => 'required|max:300',
                'sub_title_part_two.*' => 'required|max:300',
                'sub_title_part_three.*' => 'required|max:300',
                'description.*' => 'required|max:600',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Hero One Image' => 'assets/preview/adventra/hero_one.png'
        ],
        'theme' => 'adventra'
    ],
    'hero_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'sub_title_three' => 'text',
                'description' => 'text',
                'search_topic_one' => 'text',
                'search_topic_two' => 'text',
                'search_topic_three' => 'text',
                'search_topic_four' => 'text',
                'search_button' => 'text',
                'background_image' => 'file',
                'image_four' => 'file',
                'image_five' => 'file',
                'image_six' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:300',
                'sub_title_two.*' => 'nullable|max:300',
                'sub_title_three.*' => 'nullable|max:300',
                'description.*' => 'required|max:300',
                'search_topic_one.*' => 'required|max:300',
                'search_topic_two.*' => 'required|max:300',
                'search_topic_three.*' => 'required|max:300',
                'search_topic_four.*' => 'required|max:300',
                'search_button.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_four.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_five.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_six.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Hero Two Image' => 'assets/preview/adventra/hero_two.png'
        ],
        'theme' => 'adventra'
    ],
    'hero_three' => [
        'single' => [
            'field_name' => [
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'description' => 'text',
                'button_one' => 'text',
                'button_two' => 'text',
                'heading' => 'text',
                'sub_heading_one' => 'text',
                'sub_heading_two' => 'text',
                'sub_heading_three' => 'text',
                'search_topic_one' => 'text',
                'search_topic_two' => 'text',
                'search_topic_three' => 'text',
                'search_topic_four' => 'text',
                'search_button' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'sub_title_one.*' => 'required|max:300',
                'sub_title_two.*' => 'required|max:300',
                'description.*' => 'required|max:300',
                'button_one.*' => 'required|max:300',
                'button_two.*' => 'required|max:300',
                'heading.*' => 'required|max:300',
                'sub_heading_one.*' => 'required|max:300',
                'sub_heading_two.*' => 'required|max:300',
                'sub_heading_three.*' => 'required|max:300',
                'search_topic_one.*' => 'required|max:300',
                'search_topic_two.*' => 'required|max:300',
                'search_topic_three.*' => 'required|max:300',
                'search_topic_four.*' => 'required|max:300',
                'search_button.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Hero Three Image' => 'assets/preview/adventra/hero_three.png'
        ],
        'theme' => 'adventra'
    ],
    'trending_destinations' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'button' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
                'button.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme Trending Destination Image' => 'assets/preview/adventra/trending_destinations.png'
        ],
        'theme' => 'adventra'
    ],
    'about_one' => [
        'single' => [
            'field_name' => [
                'count' => 'text',
                'experience_text' => 'text',
                'title' => 'text',
                'sub_title' => 'text',
                'description' => 'text',
                'heilighted_sub_title' => 'text',
                'button' => 'text',
                'my_link' => 'url',
                'background_shape' => 'file',
                'image' => 'file',
                'image_two' => 'file',
            ],
            'validation' => [
                'count.*' => 'required|max:50',
                'experience_text.*' => 'required|max:300',
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:700',
                'description.*' => 'required|max:700',
                'heilighted_sub_title.*' => 'required|max:800',
                'button.*' => 'required|max:100',
                'my_link.*' => 'required|max:400',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'background_shape.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme About One Image' => 'assets/preview/adventra/about_one.png'
        ],
        'theme' => 'adventra'
    ],
    'about_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'description' => 'text',
                'button' => 'text',
                'my_link' => 'url',
                'call_text' => 'text',
                'call_value' => 'text',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',
                'image_four' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:700',
                'sub_title_two.*' => 'required|max:700',
                'description.*' => 'required|max:700',
                'button.*' => 'required|max:100',
                'my_link.*' => 'required|max:400',
                'call_text.*' => 'required|max:300',
                'call_value.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_three.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_four.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'icon' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:300',
                'sub_title_two.*' => 'max:300',
                'icon.*' => 'nullable|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme About Two Image' => 'assets/preview/adventra/about_two.png'
        ],
        'theme' => 'adventra'
    ],
    'about_three' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description' => 'text',
                'button' => 'text',
                'my_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:700',
                'description.*' => 'required|max:700',
                'button.*' => 'required|max:100',
                'my_link.*' => 'required|max:400',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'icon' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'icon.*' => 'nullable|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme About Three Image' => 'assets/preview/adventra/about_three.png'
        ],
        'theme' => 'adventra'
    ],
    'brand_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file',
            ],
            'validation' => [
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Brand One Image' => 'assets/preview/adventra/brand_one.png'
        ],
        'theme' => 'adventra'
    ],
    'brand_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file',
            ],
            'validation' => [
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Brand One Image' => 'assets/preview/adventra/brand_one.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'description.*' => 'required|max:900',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour One Image' => 'assets/preview/adventra/tour_one.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_three' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour One Image' => 'assets/preview/adventra/tour_one.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_discover' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
                'description_three' => 'text',
                'button' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
                'description_one.*' => 'required|max:900',
                'description_two.*' => 'required|max:900',
                'description_three.*' => 'required|max:900',
                'button.*' => 'required|max:100',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour Discover Image' => 'assets/preview/adventra/tour_discover.png'
        ],
        'theme' => 'adventra'
    ],
    'marquee_one' => [
        'multiple' => [
            'field_name' => [
                'title' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Marquee One Image' => 'assets/preview/adventra/marquee_one.png'
        ],
        'theme' => 'adventra'
    ],
    'team_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'designation' => 'text',
                'facebook' => 'text',
                'twitter' => 'text',
                'website' => 'text',
                'instagram' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'designation.*' => 'required|max:500',
                'facebook' => 'required|max:600',
                'twitter' => 'required|max:600',
                'website' => 'required|max:600',
                'instagram' => 'required|max:600',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Team One Image' => 'assets/preview/adventra/team_one.png'
        ],
        'theme' => 'adventra'
    ],
    'team_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
                'button' => 'text',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
                'button.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_three.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'designation' => 'text',
                'facebook' => 'text',
                'twitter' => 'text',
                'instagram' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'designation.*' => 'required|max:500',
                'facebook' => 'required|max:600',
                'twitter' => 'required|max:600',
                'instagram' => 'required|max:600',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Team Two Image' => 'assets/preview/adventra/team_two.png'
        ],
        'theme' => 'adventra'
    ],
    'top_destination' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
                'special_offer_title' => 'text',
                'special_offer_sub_title_one' => 'text',
                'special_offer_sub_title_two' => 'text',
                'special_offer_button' => 'text',
                'button' => 'text',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',

            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
                'special_offer_title.*' => 'required|max:300',
                'special_offer_sub_title_one.*' => 'required|max:300',
                'special_offer_sub_title_two.*' => 'required|max:300',
                'special_offer_button.*' => 'required|max:300',
                'button.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_three.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Top Deatination Image' => 'assets/preview/adventra/top_destination.png'
        ],
        'theme' => 'adventra'
    ],
    'testimonial_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
                'description_three' => 'text',

            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'address' => 'text',
                'message' => 'text',
                'rating' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'address.*' => 'required|max:300',
                'message.*' => 'required|max:300',
                'rating.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Testimonial One Image' => 'assets/preview/adventra/testimonial_one.png'
        ],
        'theme' => 'adventra'
    ],
    'testimonial_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'rating' => 'text',
                'rating_text' => 'text',
                'image' => 'file',
                'background_image' => 'file',

            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
                'rating.*' => 'required|max:300',
                'rating_text.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'address' => 'text',
                'message' => 'text',
                'rating' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'address.*' => 'required|max:300',
                'message.*' => 'required|max:300',
                'rating.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Testimonial Two Image' => 'assets/preview/adventra/testimonial_two.png'
        ],
        'theme' => 'adventra'
    ],
    'testimonial_three' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'address' => 'text',
                'message' => 'text',
                'rating' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'address.*' => 'required|max:300',
                'message.*' => 'required|max:300',
                'rating.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Testimonial Two Image' => 'assets/preview/adventra/testimonial_two.png'
        ],
        'theme' => 'adventra'
    ],
    'news_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Adventra Theme News One Image' => 'assets/preview/adventra/news_one.png'
        ],
        'theme' => 'adventra'
    ],
    'news_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Adventra Theme News Two Image' => 'assets/preview/adventra/news_two.png'
        ],
        'theme' => 'adventra'
    ],
    'news_three' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme News Three Image' => 'assets/preview/adventra/news_three.png'
        ],
        'theme' => 'adventra'
    ],
    'instagram' => [
        'multiple' => [
            'field_name' => [
                'my_link' => 'url',
                'image' => 'file',
            ],
            'validation' => [
                'my_link.*' => 'required|max:300',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Marquee One Image' => 'assets/preview/adventra/marquee_one.png'
        ],
        'theme' => 'adventra'
    ],
    'header' => [
        'single' => [
            'field_name' => [
                'call_text' => 'text',
                'call_value' => 'text',
                'address' => 'text',
                'mail_value' => 'text',
                'facebook' => 'text',
                'twitter' => 'text',
                'linkedin' => 'text',
                'instagram' => 'text',
            ],
            'validation' => [
                'call_text.*' => 'required|max:300',
                'call_value.*' => 'required|max:300',
                'mail_text.*' => 'required|max:300',
                'mail_value.*' => 'required|max:300',
                'facebook.*' => 'required|max:300',
                'twitter.*' => 'required|max:300',
                'linkedin.*' => 'required|max:300',
                'instagram.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'All Theme Header Image' => 'assets/preview/adventra/header.png'
        ],
        'theme' => 'all'
    ],
    'chose_us' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description' => 'text',
                'right_title' => 'text',
                'right_sub_title' => 'text',
                'right_description' => 'text',
                'image' => 'file',
                'image_two' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:700',
                'description.*' => 'required|max:700',
                'right_title.*' => 'required|max:700',
                'right_sub_title.*' => 'required|max:700',
                'right_description.*' => 'required|max:700',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme About One Image' => 'assets/preview/adventra/chose_us.png'
        ],
        'theme' => 'adventra'
    ],
    'marquee_two' => [
        'multiple' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme Marquee Two Image' => 'assets/preview/adventra/marquee_two.png'
        ],
        'theme' => 'adventra'
    ],
    'cta_app' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description' => 'text',
                'apple_store_link' => 'url',
                'play_store_link' => 'url',
                'background_image' => 'file',
                'image' => 'file',
                'image_two' => 'file',
                'image_three' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:700',
                'description.*' => 'required|max:700',
                'right_title.*' => 'required|max:700',
                'right_sub_title.*' => 'required|max:700',
                'right_description.*' => 'required|max:700',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image_three.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme CTA APP Image' => 'assets/preview/adventra/cta_app.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_package' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading_one' => 'text',
                'heading_two' => 'text',
                'heading_three' => 'text',
                'button' => 'text',
                'background_image' => 'file',
                'image' => 'file',
            ],
            'validation' => [
                'heading_one.*' => 'required|max:300',
                'heading_two.*' => 'required|max:500',
                'heading_three' => 'required|max:600',
                'button' => 'required|max:600',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour Package Image' => 'assets/preview/adventra/tour_package.png'
        ],
        'theme' => 'adventra'
    ],
    'faq_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'question' => 'text',
                'answer' => 'text',
            ],
            'validation' => [
                'question.*' => 'required|max:300',
                'answer.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme FAQ ONE Image' => 'assets/preview/adventra/faq_one.png'
        ],
        'theme' => 'adventra'
    ],
    'faq_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'sub_title_three' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:500',
                'sub_title_two.*' => 'required|max:500',
                'sub_title_three.*' => 'required|max:500',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'question' => 'text',
                'answer' => 'text',
            ],
            'validation' => [
                'question.*' => 'required|max:300',
                'answer.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme FAQ TWO Image' => 'assets/preview/adventra/faq_two.png'
        ],
        'theme' => 'adventra'
    ],
    'destination_one' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'button' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'button.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Adventra Theme Destination One Image' => 'assets/preview/adventra/destination_one.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_places' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:500',
                'description_one.*' => 'required|max:500',
                'description_two.*' => 'required|max:500',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour Places Image' => 'assets/preview/adventra/tour_places.png'
        ],
        'theme' => 'adventra'
    ],

    'footer' => [
        'single' => [
            'field_name' => [
                'news_letter_title' => 'text',
                'news_letter_button' => 'text',
                'call_text' => 'text',
                'call_value' => 'text',
                'mail_text' => 'text',
                'mail_value' => 'text',
                'app_text' => 'text',
                'apple_store_link' => 'url',
                'play_store_link' => 'url',
                'copyright_text' => 'text',
                'gallery_text' => 'text',
                'facebook' => 'text',
                'twitter' => 'text',
                'linkedin' => 'text',
                'instagram' => 'text',
                'background_image' => 'file',
                'background_image_two' => 'file',
            ],
            'validation' => [
                'news_letter_title.*' => 'required|max:300',
                'news_letter_button.*' => 'required|max:500',
                'call_text.*' => 'required|max:300',
                'call_value.*' => 'required|max:300',
                'mail_text.*' => 'required|max:300',
                'mail_value.*' => 'required|max:300',
                'app_text.*' => 'required|max:300',
                'apple_store_link.*' => 'required|max:300',
                'play_store_link.*' => 'required|max:300',
                'copyright_text.*' => 'required|max:300',
                'gallery_text.*' => 'required|max:300',
                'facebook.*' => 'required|max:300',
                'twitter.*' => 'required|max:300',
                'linkedin.*' => 'required|max:300',
                'instagram.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'background_image_two.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file',
                'background_image' => 'file',
            ],
            'validation' => [
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png,webp',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png,webp',
            ]
        ],
        'preview' => [
            'All Theme Footer Image' => 'assets/preview/adventra/footer.png'
        ],
        'theme' => 'all'
    ],
    'social' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'name' => 'text',
                'icon' => 'icon',
                'link' => 'url',
            ],
            'validation' => [
                'name.*' => 'required|max:300',
                'icon.*' => 'required|max:100',
                'link.*' => 'required|max:1000'
            ]
        ],
        'preview' => [
            'Homely Theme Social Image' => 'assets/preview/homely/social.png'
        ],
        'theme' => 'all'
    ],
    'cta_video' => [
        'single' => [
            'field_name' => [
                'my_link' => 'url',
                'background_image' => 'file',
            ],
            'validation' => [
                'my_link.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme CTA Video Image' => 'assets/preview/adventra/cta_video.png'
        ],
        'theme' => 'adventra'
    ],
    'tour_two' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'background_image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:300',
                'sub_title_two.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour Two Image' => 'assets/preview/adventra/tour_two.png'
        ],
        'theme' => 'adventra'
    ],
    'video' => [
        'single' => [
            'field_name' => [
                'my_link' => 'url',
                'background_image' => 'file',
            ],
            'validation' => [
                'my_link.*' => 'required|max:300',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'heading_one' => 'text',
                'heading_two' => 'text',
                'heading_three' => 'text',
                'button' => 'text',
                'background_image' => 'file',
                'image' => 'file',
            ],
            'validation' => [
                'heading_one.*' => 'required|max:300',
                'heading_two.*' => 'required|max:500',
                'heading_three' => 'required|max:600',
                'button' => 'required|max:600',
                'background_image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme Tour Package Image' => 'assets/preview/adventra/tour_package.png'
        ],
        'theme' => 'adventra'
    ],
    'contact' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title_one' => 'text',
                'sub_title_two' => 'text',
                'description_one' => 'text',
                'description_two' => 'text',
                'description_three' => 'text',
                'location_text' => 'text',
                'location' => 'text',
                'phone_text' => 'text',
                'phone' => 'text',
                'email_text' => 'text',
                'email' => 'text',
                'heading' => 'text',
                'sub_heading' => 'text',
                'button' => 'text',
                'map' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title_one.*' => 'required|max:300',
                'sub_title_two.*' => 'required|max:300',
                'description_one.*' => 'required|max:300',
                'description_two.*' => 'required|max:300',
                'description_three.*' => 'required|max:300',
                'location_text.*' => 'required|max:300',
                'location.*' => 'required|max:300',
                'phone_text.*' => 'required|max:300',
                'phone.*' => 'required|max:300',
                'email_text.*' => 'required|max:300',
                'email.*' => 'required|max:300',
                'heading.*' => 'required|max:300',
                'sub_heading.*' => 'required|max:300',
                'button.*' => 'required|max:300',
                'map.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme Contact Image' => 'assets/preview/adventra/contact.png'
        ],
        'theme' => 'adventra'
    ],
    'login' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sign_up_title' => 'text',
                'sign_up_button_name' => 'text',
                'button_name' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sign_up_title.*' => 'required|max:300',
                'sign_up_button_name.*' => 'required|max:300',
                'button_name.*' => 'required|max:300',
                'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Homely Theme Login Image' => 'assets/preview/adventra/login.png'
        ],
        'theme' => 'all'
    ],
    'register' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sign_in_title' => 'text',
                'sign_in_button_name' => 'text',
                'button_name' => 'text',
                'image' => 'file',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sign_in_title.*' => 'required|max:300',
                'sign_in_button_name.*' => 'required|max:300',
                'button_name.*' => 'required|max:300',
                'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Homely Theme Register Image' => 'assets/preview/adventra/register.png'
        ],
        'theme' => 'all'
    ],
    'plans' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Homely Theme Plans Image' => 'assets/preview/adventra/plans.png'
        ],
        'theme' => 'all'
    ],
    'pwa_popup' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'domain_name' => 'text',
                'short_description' => 'text',
                'description' => 'text',
                'image' => 'file'
            ],
            'validation' => [
                'title' => 'nullable',
                'domain_name.*' => 'nullable',
                'short_description.*' => 'nullable',
                'description' => 'nullable',
                'image.*' => 'nullable|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'multiple' => [
            'field_name' => [
                'image' => 'file'
            ],
            'validation' => [
                'image.*' => 'required|max:10240|image|mimes:jpg,jpeg,png',
            ]
        ],
        'preview' => [
            'Adventra Theme pwa'=>'assets/preview/adventra/pwa.png',
        ],
        'theme' => 'all'
    ],

    'fixed_area' => [
        'single' => [
            'field_name' => [
                'title' => 'text',
                'sub_title' => 'text',
            ],
            'validation' => [
                'title.*' => 'required|max:300',
                'sub_title.*' => 'required|max:300',
            ]
        ],
        'preview' => [
            'Adventra Theme Trending Fixed Area Image' => 'assets/preview/adventra/fixed_area.png'
        ],
        'theme' => 'adventra'
    ],

    'message' => [
        'required' => 'This field is required.',
        'min' => 'This field must be at least :min characters.',
        'max' => 'This field may not be greater than :max characters.',
        'image' => 'This field must be image.',
        'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
        'integer' => 'This field must be an integer value',
    ],

    'content_media' => [
        'image' => 'file',
        'image_two' => 'file',
        'image_three' => 'file',
        'image_four' => 'file',
        'image_five' => 'file',
        'image_six' => 'file',
        'background_image' => 'file',
        'background_image_two' => 'file',
        'background_shape' => 'file',
        'thumb_image' => 'file',
        'my_link' => 'url',
        'apple_store_link' => 'url',
        'play_store_link' => 'url',
        'icon' => 'icon',
        'count_number' => 'number',
        'start_date' => 'date'
    ]
];

