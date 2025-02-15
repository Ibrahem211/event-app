<?php

namespace App\Http\Controllers;

use App\Models\Adress;
use App\Models\Car;
use App\Models\CommentLastEvent;
use App\Models\CommentPost;
use App\Models\CommentRecent;
use App\Models\Decoration;
use App\Models\Dress_And_Makeup;
use App\Models\Event;
use App\Models\Event_comming;
use App\Models\Food;
use App\Models\Place;
use App\Models\post;
use App\Models\RatingCar;
use App\Models\RatingDecoration;
use App\Models\RatingDressAndMakeup;
use App\Models\RatingFood;
use App\Models\RatingLastEvent;
use App\Models\RatingPlace;
use App\Models\RatingSonger;
use App\Models\Songer;
use App\Models\User;
use App\Models\User_event;
use App\Models\UserLogout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BothController extends Controller
{
    /**
     * register user
     * @param Request $request
     * @return user
     */
    public function registerUser(Request $request)
    {
        try {
            $event = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => Rule::unique('users')->where(fn($query) => $query->where('is_admin', false)),
                    'password',
                    'PhoneNumber' => 'required|string|min:10|max:14|unique:users,PhoneNumber|regex:/^09/',
                    'image' => 'image|mimes:jpeg,png,jpg,gif'
                ]
            );

            if ($event->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $event->errors()
                ], 401);
            }

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'PhoneNumber' => $request->PhoneNumber,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');
                $userData['image'] = $imagePath;
            }

            $user = User::create($userData);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("pharmacist")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     *login the User
     *@param Request $request
     *@return User
     */

    public function loginUser(Request $request)
    {
        try {
            $event = validator::make(
                $request->all(),
                [
                    'PhoneNumberOremail' => 'required|string',
                    'password' => 'required',

                ]
            );

            if ($event->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $event->erorrs(),
                ], 401);
            }

            if (
                !Auth::attempt(['PhoneNumber' => $request->input('PhoneNumberOremail'), 'password' => $request->input('password')]) &&
                !Auth::attempt(['email' => $request->input('PhoneNumberOremail'), 'password' => $request->input('password')])
            ) {
                return response()->json([
                    'status' => false,
                    'message' => 'email and password is not amtch with our record.',
                ], 401);
            }

            $user = User::where('PhoneNumber', $request->PhoneNumberOremail)->orwhere('email', $request->PhoneNumberOremail)->first();
            $userLoginAgain = UserLogout::where('user_id', $user->id)->delete();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User logged in successfully.',
                    'token' => $user->createToken('company')->plainTextToken
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'false',
                'massage' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * logout the user
     */

    public function logoutUser(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            UserLogout::create([
                'user_id' => $user->id,
                'logged_out_at' => now(),
            ]);
        }
        $request->user()->tokens()->delete();
        return response([
            'message' => 'logged out'
        ], 200);
    }

    public function delete_acounte()
    {
        User::where("id", Auth::user()->id)->delete();
        return response()->json([
            "message" => "deleted"
        ], 200);
    }

    public function showDecorations()
    {
        $allDecorations = Decoration::all();
        $parentDecorations = [];

        foreach ($allDecorations as $decoration) {
            if ($decoration->parent_id === null) {
                $parentDecorations[] = [
                    'id' => $decoration->id,
                    'categories' => $decoration->type,
                    'image' => Storage::url($decoration->image),
                    'children' => []
                ];
            } else {
                foreach ($parentDecorations as &$parentDecoration) {
                    if ($parentDecoration['id'] === $decoration->parent_id) {

                        $averageRating = $decoration->ratings->avg('rating');
                        $averageRating = round($averageRating, 1);

                        $totalRatings = $decoration->ratings->count();

                        $ratingsPercentage = [
                            '1' => ($totalRatings > 0) ? round(($decoration->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                            '2' => ($totalRatings > 0) ? round(($decoration->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                            '3' => ($totalRatings > 0) ? round(($decoration->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                            '4' => ($totalRatings > 0) ? round(($decoration->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                            '5' => ($totalRatings > 0) ? round(($decoration->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                        ];

                        $ratingsCount = [
                            '1' => $decoration->ratings->where('rating', 1)->count(),
                            '2' => $decoration->ratings->where('rating', 2)->count(),
                            '3' => $decoration->ratings->where('rating', 3)->count(),
                            '4' => $decoration->ratings->where('rating', 4)->count(),
                            '5' => $decoration->ratings->where('rating', 5)->count(),
                        ];

                        $parentDecoration['children'][] = [
                            'id' => $decoration->id,
                            'categories' => $decoration->type,
                            'price' => $decoration->price,
                            'description' => $decoration->description,
                            'image' => Storage::url($decoration->image),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];
                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentDecorations,
            'message' => 'decoration menu'
        ], 200);
    }

    public function showPlaces()
    {
        $allPlaces = Place::with(['address', 'images'])->get();
        $parentCategories = [];

        foreach ($allPlaces as $place) {
            if ($place->parent_id === null) {
                $parentCategories[] = [
                    'id' => $place->id,
                    'categories' => $place->name,
                    'images' => $place->images->map(function ($image) {
                        return [
                            'image' => Storage::url($image->image),
                        ];
                    }),
                    'children' => []
                ];
            } else {
                foreach ($parentCategories as &$parentCategory) {
                    if ($parentCategory['id'] === $place->parent_id) {
                        $averageRating = 0.0;
                        $totalRatings = 0;
                        $ratingsPercentage = [
                            '1' => 0,
                            '2' => 0,
                            '3' => 0,
                            '4' => 0,
                            '5' => 0,
                        ];

                        if ($place->ratings->count() > 0) {
                            $averageRating = $place->ratings->avg('rating');
                            $averageRating = round($averageRating, 1);
                            $totalRatings = $place->ratings->count();

                            $ratingsPercentage = [
                                '1' => ($totalRatings > 0) ? round(($place->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                                '2' => ($totalRatings > 0) ? round(($place->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                                '3' => ($totalRatings > 0) ? round(($place->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                                '4' => ($totalRatings > 0) ? round(($place->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                                '5' => ($totalRatings > 0) ? round(($place->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                            ];
                        }

                        $ratingsCount = [
                            '1' => $place->ratings->where('rating', 1)->count(),
                            '2' => $place->ratings->where('rating', 2)->count(),
                            '3' => $place->ratings->where('rating', 3)->count(),
                            '4' => $place->ratings->where('rating', 4)->count(),
                            '5' => $place->ratings->where('rating', 5)->count(),
                        ];

                        $parentCategory['children'][] = [
                            'id' => $place->id,
                            'categories' => $place->name,
                            'price' => $place->price,
                            'PhoneNumber' => $place->PhoneNumber,
                            'description' => $place->description,
                            'tele' => $place->tele,
                            'address_name' => $place->address->name,
                            'parent_name' => $place->address->parent ? $place->address->parent->name : null,
                            'images' => $place->images->map(function ($image) {
                                return [
                                    'image' => Storage::url($image->image),
                                ];
                            }),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];
                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentCategories,
            'message' => 'All places and categories',
        ], 200);
    }

    public function showDress_and_makeups()
    {
        $allDressAndMakeups = Dress_And_Makeup::all();
        $parentDressAndMakeups = [];

        foreach ($allDressAndMakeups as $dressAndMakeup) {
            if ($dressAndMakeup->parent_id === null) {
                $parentDressAndMakeups[] = [
                    'id' => $dressAndMakeup->id,
                    'categories' => $dressAndMakeup->type,
                    'image' => Storage::url($dressAndMakeup->image),
                    'children' => []
                ];
            } else {
                foreach ($parentDressAndMakeups as &$parentDressAndMakeup) {
                    if ($parentDressAndMakeup['id'] === $dressAndMakeup->parent_id) {

                        $averageRating = $dressAndMakeup->ratings->avg('rating');
                        $averageRating = round($averageRating, 1);

                        $totalRatings = $dressAndMakeup->ratings->count();

                        $ratingsPercentage = [
                            '1' => ($totalRatings > 0) ? round(($dressAndMakeup->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                            '2' => ($totalRatings > 0) ? round(($dressAndMakeup->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                            '3' => ($totalRatings > 0) ? round(($dressAndMakeup->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                            '4' => ($totalRatings > 0) ? round(($dressAndMakeup->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                            '5' => ($totalRatings > 0) ? round(($dressAndMakeup->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                        ];

                        $ratingsCount = [
                            '1' => $dressAndMakeup->ratings->where('rating', 1)->count(),
                            '2' => $dressAndMakeup->ratings->where('rating', 2)->count(),
                            '3' => $dressAndMakeup->ratings->where('rating', 3)->count(),
                            '4' => $dressAndMakeup->ratings->where('rating', 4)->count(),
                            '5' => $dressAndMakeup->ratings->where('rating', 5)->count(),
                        ];

                        $parentDressAndMakeup['children'][] = [
                            'id' => $dressAndMakeup->id,
                            'categories' => $dressAndMakeup->type,
                            'price' => $dressAndMakeup->price,
                            'description' => $dressAndMakeup->description,
                            'image' => Storage::url($dressAndMakeup->image),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];

                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentDressAndMakeups,
            'message' => 'dress and makeup menu'
        ], 200);
    }

    public function showSonger()
    {
        $allSongers = Songer::all();
        $parentSongers = [];

        foreach ($allSongers as $songer) {
            if ($songer->parent_id === null) {
                $parentSongers[] = [
                    'id' => $songer->id,
                    'categories' => $songer->name,
                    'image' => Storage::url($songer->image),
                    'children' => []
                ];
            } else {
                foreach ($parentSongers as &$parentSonger) {
                    if ($parentSonger['id'] === $songer->parent_id) {

                        $averageRating = $songer->ratings->avg('rating');
                        $averageRating = round($averageRating, 1);

                        $totalRatings = $songer->ratings->count();

                        $ratingsPercentage = [
                            '1' => ($totalRatings > 0) ? round(($songer->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                            '2' => ($totalRatings > 0) ? round(($songer->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                            '3' => ($totalRatings > 0) ? round(($songer->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                            '4' => ($totalRatings > 0) ? round(($songer->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                            '5' => ($totalRatings > 0) ? round(($songer->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                        ];

                        $ratingsCount = [
                            '1' => $songer->ratings->where('rating', 1)->count(),
                            '2' => $songer->ratings->where('rating', 2)->count(),
                            '3' => $songer->ratings->where('rating', 3)->count(),
                            '4' => $songer->ratings->where('rating', 4)->count(),
                            '5' => $songer->ratings->where('rating', 5)->count(),
                        ];

                        $parentSonger['children'][] = [
                            'id' => $songer->id,
                            'categories' => $songer->name,
                            'description' => $songer->description,
                            'price' => $songer->price,
                            'image' => Storage::url($songer->image),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];
                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentSongers,
            'message' => 'songer menu'
        ], 200);
    }

    public function showCar()
    {
        $allCars = Car::all();
        $parentCars = [];

        foreach ($allCars as $car) {
            if ($car->parent_id === null) {
                $parentCars[] = [
                    'id' => $car->id,
                    'categories' => $car->name,
                    'image' => Storage::url($car->image),
                    'children' => []
                ];
            } else {
                foreach ($parentCars as &$parentCar) {
                    if ($parentCar['id'] === $car->parent_id) {

                        $averageRating = $car->ratings->avg('rating');
                        $averageRating = round($averageRating, 1);

                        $totalRatings = $car->ratings->count();

                        $ratingsPercentage = [
                            '1' => ($totalRatings > 0) ? round(($car->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                            '2' => ($totalRatings > 0) ? round(($car->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                            '3' => ($totalRatings > 0) ? round(($car->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                            '4' => ($totalRatings > 0) ? round(($car->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                            '5' => ($totalRatings > 0) ? round(($car->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                        ];

                        $ratingsCount = [
                            '1' => $car->ratings->where('rating', 1)->count(),
                            '2' => $car->ratings->where('rating', 2)->count(),
                            '3' => $car->ratings->where('rating', 3)->count(),
                            '4' => $car->ratings->where('rating', 4)->count(),
                            '5' => $car->ratings->where('rating', 5)->count(),
                        ];

                        $parentCar['children'][] = [
                            'id' => $car->id,
                            'categories' => $car->name,
                            'price' => $car->price,
                            'description' => $car->description,
                            'image' => Storage::url($car->image),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];
                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentCars,
            'message' => 'car menu'
        ], 200);
    }

    public function showFood()
    {
        $allFood = Food::with('ratings')->get();
        $parentCategories = [];

        foreach ($allFood as $food) {
            if ($food->parent_id === null) {
                $parentCategories[] = [
                    'id' => $food->id,
                    'categories' => $food->categories,
                    'image' => Storage::url($food->image),
                    'children' => []
                ];
            } else {
                foreach ($parentCategories as &$parentCategory) {
                    if ($parentCategory['id'] === $food->parent_id) {

                        $averageRating = $food->ratings->avg('rating');
                        $averageRating = round($averageRating, 1);

                        $totalRatings = $food->ratings->count();

                        $ratingsPercentage = [
                            '1' => ($totalRatings > 0) ? round(($food->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                            '2' => ($totalRatings > 0) ? round(($food->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                            '3' => ($totalRatings > 0) ? round(($food->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                            '4' => ($totalRatings > 0) ? round(($food->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                            '5' => ($totalRatings > 0) ? round(($food->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                        ];

                        $ratingsCount = [
                            '1' => $food->ratings->where('rating', 1)->count(),
                            '2' => $food->ratings->where('rating', 2)->count(),
                            '3' => $food->ratings->where('rating', 3)->count(),
                            '4' => $food->ratings->where('rating', 4)->count(),
                            '5' => $food->ratings->where('rating', 5)->count(),
                        ];

                        $parentCategory['children'][] = [
                            'id' => $food->id,
                            'categories' => $food->categories,
                            'price' => $food->price,
                            'description' => $food->description,
                            'image' => Storage::url($food->image),
                            'average_rating' => $averageRating,
                            'total_ratings' => $totalRatings,
                            'ratings_percentage' => $ratingsPercentage,
                            'ratings_count' => $ratingsCount
                        ];
                        break;
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $parentCategories,
            'message' => 'food menu '
        ], 200);
    }

    function getUserProfile()
    {
        $user = Auth::user();
        if ($user) {
            $imagePath = $user->image;
            $profile = [
                'name' => $user->name,
                'email' => $user->email,
                'PhoneNumber' => $user->PhoneNumber,
                'image' => Storage::url($imagePath),
                'is_admin' => $user->is_admin,
            ];
            return $profile;
        }

        return null;
    }

    public function getCategoriesByQuery(Request $request)
    {
        $query = $request->input('query');
        if ($query == 'event') {
            $categoiesevent = Event::get(['id', 'categories', 'image']);
            return $categoiesevent;
        } elseif ($query == 'place') {
            $categoiesplace = Place::whereNull('parent_id')
                ->with('images')
                ->get(['id', 'name'])
                ->map(function ($place) {
                    return [
                        'id' => $place->id,
                        'name' => $place->name,
                        'image' => $place->images->pluck('image')->first()
                    ];
                });
            return $categoiesplace;
        } elseif ($query == 'songer') {
            $categoiesSonger = Songer::whereNull('parent_id')
                ->get(['id', 'name', 'image']);
            return $categoiesSonger;
        } elseif ($query == 'car') {
            $categoiesCar = Car::whereNull('parent_id')
                ->get(['id', 'name', 'image']);
            return $categoiesCar;
        } elseif ($query == 'food') {
            $categoiesFood = Food::whereNull('parent_id')
                ->get(['id', 'categories', 'image']);
            return $categoiesFood;
        } elseif ($query == 'decoration') {
            $categoiesdecoration = Decoration::whereNull('parent_id')
                ->get(['id', 'type', 'image']);
            return $categoiesdecoration;
        } elseif ($query == 'dress and makeup') {
            $categoiesDress_And_Makeup = Dress_And_Makeup::whereNull('parent_id')
                ->get(['id', 'type', 'image']);
            return $categoiesDress_And_Makeup;
        }
    }

    public function EditUserProfile(Request $request)
    {
        $user = auth()->user();

        $userData = [];

        if (isset($request->name)) {
            $userData['name'] = $request->name;
        }

        if (isset($request->phone_number) && $request->phone_number !== $user->PhoneNumber) {
            $existingUser = DB::table('users')
                ->where('PhoneNumber', $request->phone_number)
                ->orWhere('email', $request->phone_number)
                ->first();
            if ($existingUser) {
                return response()->json(["error" => "The phone number is already in use"], 400);
            }
            $userData['PhoneNumber'] = $request->phone_number;
        }

        if (isset($request->email) && $request->email !== $user->email) {
            $existingUser = DB::table('users')
                ->where('email', $request->email)
                ->orWhere('PhoneNumber', $request->email)
                ->first();
            if ($existingUser) {
                return response()->json(["error" => "The email is already in use"], 400);
            }
            $userData['email'] = $request->email;
        }

        if ($request->has('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(["error" => "The old password is incorrect"], 400);
            }

            if ($request->new_password !== $request->password_confirmation) {
                return response()->json(["error" => "Confirm new password does not match"], 400);
            }

            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filePath = $file->store('profile_images', 'public');
            $userData['image'] = $filePath;
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update($userData);

        return response()->json(["message" => "The profile has been successfully modified"]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchItem = $request->input('item');
        $subresultsmat = [];
        if ($query == 'place') {
            $results = Place::where('name', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->with('address')->get();
            return $results;
            foreach ($results as $result) {

                $category = Place::findOrFail($result->parent_id);
                if ($result->address->parent_id === null) {

                    $subresultsmat[] = [
                        'id' => $result->id,
                        'name' => $result->name,
                        'category' => $category->name,
                        'address_id' => $result->address->id,
                        'address name' => $result->address->name,
                        'price' => $result->price,
                    ];
                } else {
                    $central = Adress::findOrFail($result->address->parent_id);
                    $subresultsmat['subAddress'][] = [
                        'id' => $result->id,
                        'name' => $result->name,
                        'category' => $category->name,
                        'address_id' => $result->address->id,
                        'address name' => $result->address->name,
                        'central address name' => $central->name,
                        'price' => $result->price,
                    ];
                }
            }
        } elseif ($query == 'food') {
            $results = Food::where('categories', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->get();
            foreach ($results as $result) {
                $category = Food::findOrFail($result->parent_id);
                $subresultsmat[] = [
                    'id' => $result->id,
                    'name' => $result->categories,
                    'category' => $category->categories,
                    'price' => $result->price,
                ];
            }
        } elseif ($query == 'decoration') {
            $results = Decoration::where('type', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->get();
            foreach ($results as $result) {

                $category = Decoration::findOrFail($result->parent_id);
                $subresultsmat[] = [
                    'id' => $result->id,
                    'name' => $result->type,
                    'category' => $category->type,
                    'price' => $result->price,
                ];
            }
        } elseif ($query == 'car') {
            $results = Car::where('name', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->get();
            foreach ($results as $result) {

                $category = car::findOrFail($result->parent_id);
                $subresultsmat[] = [
                    'id' => $result->id,
                    'name' => $result->name,
                    'category' => $category->name,
                    'price' => $result->price,
                ];
            }
        } elseif ($query == 'dress_and_makeup') {
            $results = Dress_And_Makeup::where('type', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->get();
            foreach ($results as $result) {

                $category = Dress_And_Makeup::findOrFail($result->parent_id);
                $subresultsmat[] = [
                    'id' => $result->id,
                    'name' => $result->type,
                    'category' => $category->type,
                    'price' => $result->price,
                ];
            }
        } elseif ($query == 'songer') {
            $results = Songer::where('name', 'like', '%' . $searchItem . '%')
                ->where('parent_id', '!=', NULL)
                ->get();
            foreach ($results as $result) {

                $category = Songer::findOrFail($result->parent_id);
                $subresultsmat[] = [
                    'id' => $result->id,
                    'name' => $result->name,
                    'category' => $category->name,
                    'price' => $result->price,
                ];
            }
        } else {
            return response()->json(['message' => 'Invalid query'], 400);
        }

        if ($results->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'not found'
            ]);
        }

        return response()->json([
            'data' => $subresultsmat
        ], 200);
    }

    public function showSelectItem(Request $request)
    {
        $query = $request->input('query');
        $id = $request->input('id');
        if ($query == 'place') {
            $results = Place::where('id', $id)->with('address')->first();
            $category = Place::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            if ($results->address->parent_id === null) {

                $subresultsmat[] = [
                    'id' => $results->id,
                    'name' => $results->name,
                    'category' => $category->name,
                    'address_id' => $results->address->id,
                    'address name' => $results->address->name,
                    'price' => $results->price,
                    'PhoneNumber' => $results->PhoneNumber,
                    'description' => $results->description,
                    'tele' => $results->tele,
                    'images' => $results->images->map(function ($image) {
                        return [
                            'image' => Storage::url($image->image),
                        ];
                    }),
                ];
            } else {
                $centraladd = Place::findOrFail($results->address->parent_id);
                $subresultsmat['subAddress'][] = [
                    'id' => $results->id,
                    'name' => $results->name,
                    'address_id' => $results->address->id,
                    'address name' => $results->address->name,
                    'central address name' => $centraladd->name,
                    'price' => $results->price,
                    'PhoneNumber' => $results->PhoneNumber,
                    'description' => $results->description,
                    'tele' => $results->tele,
                    'images' => $results->images->map(function ($image) {
                        return [
                            'image' => Storage::url($image->image),
                        ];
                    }),
                ];
            }
        } elseif ($query == 'food') {
            $results = Food::findOrFail($id);
            $category = Food::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            $subresultsmat[] = [
                'id' => $results->id,
                'name' => $results->categories,
                'category' => $category->categories,
                'price' => $results->price,
                'description' => $results->description,
                'image' => $results->image
            ];
        } elseif ($query == 'decoration') {
            $results = Decoration::findOrFail($id);
            $category = Decoration::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            $subresultsmat[] = [
                'id' => $results->id,
                'name' => $results->type,
                'category' => $category->type,
                'price' => $results->price,
                'description' => $results->description,
                'image' => $results->image
            ];
        } elseif ($query == 'car') {
            $results = Car::findOrFail($id);
            $category = car::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            $subresultsmat[] = [
                'id' => $results->id,
                'name' => $results->name,
                'category' => $category->name,
                'price' => $results->price,
                'description' => $results->description,
                'image' => $results->image
            ];
        } elseif ($query == 'dress_and_makeup') {
            $results = Dress_And_Makeup::findOrFail($id);
            $category = Dress_And_Makeup::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            $subresultsmat[] = [
                'id' => $results->id,
                'name' => $results->type,
                'category' => $category->type,
                'price' => $results->price,
                'description' => $results->description,
                'image' => $results->image
            ];
        } elseif ($query == 'songer') {
            $results = Songer::findOrFail($id);
            $category = car::where('id', $results->parent_id)->first();
            if ($category === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'inviled id (it is a category)'
                ]);
            }
            $subresultsmat[] = [
                'id' => $results->id,
                'name' => $results->name,
                'category' => $category->name,
                'price' => $results->price,
                'description' => $results->description,
                'image' => $results->image
            ];
        }

        return response()->json([
            'data' => $subresultsmat
        ], 200);
    }

    public function ShowAllsonger()
    {
        $childrenData = Songer::with('parent:id,name')
            ->whereNotNull('parent_id')
            ->get(['id', 'name', 'parent_id', 'price', 'description', 'image']);

        $dataWithParentNames = $childrenData->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'categories' => optional($item->parent)->name,
                'price' => $item->price,
                'description' => $item->description,
                'image' => Storage::url($item->image)
            ];
        });

        return $dataWithParentNames;
    }

    public function ShowAllDecorations()
    {
        $childrenData = Decoration::with('parent:id,type')
            ->whereNotNull('parent_id')
            ->get(['id', 'parent_id', 'type', 'price', 'description', 'image']);

        $dataWithParentInfo = $childrenData->map(function ($item) {
            return [
                'id' => $item->id,
                'categories' => optional($item->parent)->type,
                'type' => $item->type,
                'price' => $item->price,
                'description' => $item->description,
                'image' => Storage::url($item->image)
            ];
        });

        return $dataWithParentInfo;
    }

    public function ShowAllCars()
    {
        $childrenData = Car::with('parent:id,name')
            ->whereNotNull('parent_id')
            ->get(['id', 'name', 'parent_id', 'price', 'description', 'image']);

        $dataWithParentNames = $childrenData->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'categories' => optional($item->parent)->name,
                'price' => $item->price,
                'description' => $item->description,
                'image' => Storage::url($item->image)
            ];
        });

        return $dataWithParentNames;
    }

    public function ShowAllPlaces()
    {
        $placesData = Place::with(['parent:id,name', 'address', 'images'])
            ->whereNotNull('parent_id')
            ->get([
                'id',
                'name',
                'parent_id',
                'adress_id',
                'price',
                'PhoneNumber',
                'description',
                'tele'
            ]);

        $dataWithParentAndAddress = $placesData->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'categories' => optional($item->parent)->name,
                'address' => $item->address->name,
                'city' => $item->address->parent ? $item->address->parent->name : null,
                'price' => $item->price,
                'phone_number' => $item->PhoneNumber,
                'description' => $item->description,
                'telephone' => $item->tele,
                'images' => $item->images->map(function ($image) {
                    return [
                        'image' => Storage::url($image->image),
                    ];
                }),
            ];
        });

        return $dataWithParentAndAddress;
    }

    public function ShowAllFood()
    {
        $foodData = Food::with('parent:id,categories')
            ->whereNotNull('parent_id')
            ->get(['id', 'categories', 'parent_id', 'price', 'description', 'image']);

        $dataWithParentInfo = $foodData->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->categories,
                'categories' => optional($item->parent)->categories,
                'price' => $item->price,
                'description' => $item->description,
                'image' => Storage::url($item->image)
            ];
        });

        return $dataWithParentInfo;
    }

    public function ShowAllDressAndMakeups()
    {
        $dressAndMakeupData = Dress_And_Makeup::with('parent:id,type')
            ->whereNotNull('parent_id')
            ->get(['id', 'type', 'parent_id', 'price', 'description', 'image']);
        $dataWithParentInfo = $dressAndMakeupData->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => $item->type,
                'categories' => optional($item->parent)->type,
                'price' => $item->price,
                'description' => $item->description,
                'image' => Storage::url($item->image)
            ];
        });

        return $dataWithParentInfo;
    }

    public function getAllServices()
    {
        $services = [
            'songers' => $this->ShowAllSonger(),
            'Decorations' => $this->ShowAllDecorations(),
            'Cars' => $this->ShowAllCars(),
            'Places' => $this->ShowAllPlaces(),
            'Foods' => $this->ShowAllFood(),
            'Dresses & Makeups' => $this->ShowAllDressAndMakeups(),
        ];

        return $services;
    }

    public function ShowAllPost()
    {
        $posts = Post::with('user')->get();

        return response()->json([
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'user' => $post->user->name,
                    'post' => $post->post,
                    'created_at' => $post->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function createPost(Request $request)
    {
        $validatedData = $request->validate([
            'post' => 'required|string',
        ]);

        $user = auth()->user();

        $post = $user->posts()->create([
            'post' => $validatedData['post'],
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
        ]);
    }

    public function updatePost(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'post' => 'required|string',
        ]);

        $user = auth()->user();
        $post = $user->posts()->find($validatedData['id'])->update([
            'post' => $validatedData['post'],
        ]);

        return response()->json([
            'data' => $post,
            'message' => 'Post updated successfully',
        ]);
    }

    public function deletePost(Request $request)
    {
        $validatedData = $request->input('id');

        $user = auth()->user();
        $post = $user->posts()->findOrFail($validatedData);

        $post->CommentPosts()->delete();

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }

    public function AddCommentsPost(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = new CommentPost([
            'user_id' => auth()->id(),
            'post_id' => $validatedData['post_id'],
            'comment' => $validatedData['comment'],
        ]);

        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'status' => true,
        ]);
    }

    public function updateComment(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = CommentPost::findOrFail($validatedData['id']);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this comment.'
            ], 403);
        }

        $comment->comment = $validatedData['comment'];
        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully.',
            'status' => true,
        ]);
    }

    public function deleteComment(Request $request)
    {
        $validatedData = $request->input('id');

        $comment = CommentPost::findOrFail($validatedData);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function getCommentsByPost(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $comments = CommentPost::where('post_id', $validatedData['id'])
            ->with('user')->get();

        return response()->json([
            'posts' => $comments->map(function ($comments) {
                return [
                    'user' => $comments->user->name,
                    'comment_id' => $comments->id,
                    'comment' => $comments->comment,
                    'created_at' => $comments->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function AddCommentsLast(Request $request)
    {
        $validatedData = $request->validate([
            'user_event_id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = new CommentLastEvent([
            'user_id' => auth()->id(),
            'user_event_id' => $validatedData['user_event_id'],
            'comment' => $validatedData['comment'],
        ]);

        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'status' => true,
        ]);
    }

    public function updateCommentlast(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = CommentLastEvent::findOrFail($validatedData['id']);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this comment.'
            ], 403);
        }

        $comment->comment = $validatedData['comment'];
        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully.',
            'status' => true,
        ]);
    }

    public function deleteCommentlast(Request $request)
    {
        $validatedData = $request->input('id');

        $comment = CommentLastEvent::findOrFail($validatedData);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function getCommentsByLast(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $comments = CommentLastEvent::where('user_event_id', $validatedData['id'])
            ->with('user')->get();

        return response()->json([
            'posts' => $comments->map(function ($comments) {
                return [
                    'user' => $comments->user->name,
                    'id' => $comments->id,
                    'comment' => $comments->comment,
                    'created_at' => $comments->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function AddCommentsRecent(Request $request)
    {
        $validatedData = $request->validate([
            'event_comming_id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = new CommentRecent([
            'user_id' => auth()->id(),
            'event_comming_id' => $validatedData['event_comming_id'],
            'comment' => $validatedData['comment'],
        ]);

        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully',
            'status' => true,
        ]);
    }

    public function updateCommentRecent(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'comment' => 'required|string',
        ]);

        $comment = CommentRecent::findOrFail($validatedData['id']);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this comment.'
            ], 403);
        }

        $comment->comment = $validatedData['comment'];
        $comment->save();

        return response()->json([
            'message' => 'Comment updated successfully.',
            'status' => true,
        ]);
    }

    public function deleteCommentRecent(Request $request)
    {
        $validatedData = $request->input('id');

        $comment = CommentRecent::findOrFail($validatedData);

        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function getCommentsByRecent(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $comments = CommentRecent::where('event_comming_id', $validatedData['id'])
            ->with('user')->get();

        return response()->json([
            'posts' => $comments->map(function ($comments) {
                return [
                    'user' => $comments->user->name,
                    'id' => $comments->id,
                    'comment' => $comments->comment,
                    'created_at' => $comments->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function rating(Request $request)
    {
        $user = auth()->user()->id;
        $type = $request->input('type');

        switch ($type) {
            case 'place':
                $ifExist = RatingPlace::where('place_id', $request->place_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $rating = RatingPlace::create([
                    'user_id' => $user,
                    'place_id' => $request->place_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'dress_and_makeup':
                $ifExist = RatingDressAndMakeup::where('dress_and_makeup_id', $request->dress_and_makeup_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $rating = RatingDressAndMakeup::create([
                    'user_id' => $user,
                    'dress_and_makeup_id' => $request->dress_and_makeup_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'songer':
                $ifExist = RatingSonger::where('songer_id', $request->songer_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $rating = RatingSonger::create([
                    'user_id' => $user,
                    'songer_id' => $request->songer_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'car':
                $ifExist = Ratingcar::where('user_id', $user)
                    ->where('car_id', $request->car_id)
                    ->exists();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $car = Car::find($request->car_id);

                if (!$car) {
                    return response()->json([
                        'message' => 'Invalid car_id'
                    ], 400);
                }

                $rating = Ratingcar::create([
                    'user_id' => $user,
                    'car_id' => $request->car_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'food':
                $ifExist = RatingFood::where('user_id', $user)
                    ->where('food_id', $request->food_id)
                    ->exists();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $food = Food::find($request->food_id);

                if (!$food) {
                    return response()->json([
                        'message' => 'Invalid food_id'
                    ], 400);
                }

                $rating = RatingFood::create([
                    'user_id' => $user,
                    'food_id' => $request->food_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'decoration':
                $ifExist = RatingDecoration::where('decoration_id', $request->decoration_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $rating = RatingDecoration::create([
                    'user_id' => $user,
                    'decoration_id' => $request->decoration_id,
                    'rating' => $request->rating
                ]);
                break;

            case 'last_event':
                $ifExist = RatingLastEvent::where('last_event_id', $request->last_event_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    return response()->json([
                        'message' => 'It has been rating before'
                    ]);
                }

                $rating = RatingLastEvent::create([
                    'user_id' => $user,
                    'last_event_id' => $request->last_event_id,
                    'rating' => $request->rating
                ]);
                break;

            default:
                return response()->json([
                    'message' => 'Invalid type'
                ], 400);
        }

        return response()->json([
            'message' => 'Added successfully'
        ], 200);
    }

    public function getAddress()
    {
        $parentAddresses = Adress::whereNull('parent_id')
            ->with('children')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $parentAddresses
        ], 200);
    }

    public function getEvent()
    {
        $userEvents = User_event::with(['user:id,name', 'images', 'comments', 'ratings'])
            ->get()
            ->map(function ($userEvent) {
                $totalRatings = $userEvent->ratings->count();
                $averageRating = $userEvent->ratings->avg('rating');
                $averageRating = round($averageRating, 1);

                $ratingsPercentage = [
                    '1' => ($totalRatings > 0) ? round(($userEvent->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                    '2' => ($totalRatings > 0) ? round(($userEvent->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                    '3' => ($totalRatings > 0) ? round(($userEvent->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                    '4' => ($totalRatings > 0) ? round(($userEvent->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                    '5' => ($totalRatings > 0) ? round(($userEvent->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                ];

                $ratingsCount = [
                    '1' => $userEvent->ratings->where('rating', 1)->count(),
                    '2' => $userEvent->ratings->where('rating', 2)->count(),
                    '3' => $userEvent->ratings->where('rating', 3)->count(),
                    '4' => $userEvent->ratings->where('rating', 4)->count(),
                    '5' => $userEvent->ratings->where('rating', 5)->count(),
                ];

                return [
                    'user_id' => $userEvent->user->id,
                    'user_name' => $userEvent->user->name,
                    'images' => $userEvent->images->map(function ($imageLast) {
                        return [
                            'title' => $imageLast->title,
                            'image' => Storage::url($imageLast->image)
                        ];
                    }),
                    'comments' => $userEvent->comments->map(function ($comment) {
                        return [
                            'comment' => $comment->comment
                        ];
                    }),
                    'average_rating' => $averageRating,
                    'total_ratings' => $totalRatings,
                    'ratings_percentage' => $ratingsPercentage,
                    'ratings_count' => $ratingsCount
                ];
            });

        $Adminevents = Event_comming::with('image_comming')
            ->get()
            ->map(function ($eventComming) {
                return [
                    'event_id' => $eventComming->id,
                    'name' => $eventComming->name,
                    'type' => $eventComming->type,
                    'price' => $eventComming->price,
                    'location' => $eventComming->location,
                    'description' => $eventComming->description,
                    'Number_of_attendees' => $eventComming->Number_of_attendees,
                    'Number_of_tickets' => $eventComming->Number_of_tickets,
                    'date' => $eventComming->date,
                    'images' => $eventComming->image_comming->map(function ($imageComming) {
                        return [
                            'image' => Storage::url($imageComming->image)
                        ];
                    })
                ];
            });

        return response()->json([
            'userEvents' => $userEvents,
            'Adminevents' => $Adminevents
        ]);
    }

    public function getEventDetails(Request $request)
    {
        $eventId = $request->input('id');
        $eventDetails = User_event::with(['user:id,name', 'images', 'place', 'decoration', 'food', 'drees_and_makeup', 'songer', 'car', 'comments', 'ratings'])
            ->where('id', $eventId)
            ->first();

        if (!$eventDetails) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $totalRatings = $eventDetails->ratings->count();
        $averageRating = $eventDetails->ratings->avg('rating');
        $averageRating = round($averageRating, 1);

        $ratingsPercentage = [
            '1' => ($totalRatings > 0) ? round(($eventDetails->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
            '2' => ($totalRatings > 0) ? round(($eventDetails->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
            '3' => ($totalRatings > 0) ? round(($eventDetails->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
            '4' => ($totalRatings > 0) ? round(($eventDetails->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
            '5' => ($totalRatings > 0) ? round(($eventDetails->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
        ];

        $ratingsCount = [
            '1' => $eventDetails->ratings->where('rating', 1)->count(),
            '2' => $eventDetails->ratings->where('rating', 2)->count(),
            '3' => $eventDetails->ratings->where('rating', 3)->count(),
            '4' => $eventDetails->ratings->where('rating', 4)->count(),
            '5' => $eventDetails->ratings->where('rating', 5)->count(),
        ];

        return response()->json([
            'user_id' => $eventDetails->user->id,
            'user_name' => $eventDetails->user->name,
            'date' => $eventDetails->date,
            'photography' => $eventDetails->photography,
            'status' => $eventDetails->status,
            'viewability' => $eventDetails->viewability,
            'completed' => $eventDetails->completed,
            'place' => $eventDetails->place->name,
            'decoration' => $eventDetails->decoration->type,
            'food' => $eventDetails->food->categories,
            'dressAndMakeup' => $eventDetails->drees_and_makeup->type,
            'songer' => $eventDetails->songer->name,
            'car' => $eventDetails->car->name,
            'images' => $eventDetails->images->map(function ($image) {
                return [
                    'title' => $image->title,
                    'image' => Storage::url($image->image)
                ];
            }),
            'comments' => $eventDetails->comments->map(function ($comment) {
                return [
                    'comment' => $comment->comment
                ];
            }),
            'average_rating' => $averageRating,
            'total_ratings' => $totalRatings,
            'ratings_percentage' => $ratingsPercentage,
            'ratings_count' => $ratingsCount
        ]);
    }
}
