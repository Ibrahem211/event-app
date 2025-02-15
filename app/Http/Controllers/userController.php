<?php

namespace App\Http\Controllers;

use App\Models\Adress;
use App\Models\Car;
use App\Models\Decoration;
use App\Models\Dress_And_Makeup;
use App\Models\Event;
use App\Models\Event_Comming;
use App\Models\Favorite_Car;
use App\Models\Favorite_decoration;
use App\Models\Favorite_Dress_And_Makeup;
use App\Models\Favorite_Food;
use App\Models\Favorite_Place;
use App\Models\Favorite_Songer;
use App\Models\Food;
use App\Models\Place;
use App\Models\quistion;
use App\Models\Songer;
use App\Models\User;
use App\Models\User_comming;
use App\Models\User_event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{

    public function recentEvents(Request $request)
    {
        $eventWillShow = [];
        $today = Carbon::today();
        $recentEvents = Event_Comming::whereDate('date', '>=', $today)
            ->get();

        foreach ($recentEvents as $recentEvent) {
            $usersCount = $recentEvent->users()->count();
            if ($recentEvent->Number_of_attendees !== $usersCount) {
                $images = $recentEvent->image_comming->pluck('image')->toArray();
                $eventImages = array_map(function ($image) {
                    return Storage::url($image);
                }, $images);

                $recentEvent->images = $eventImages;
                unset($recentEvent->image_comming);

                $eventWillShow[] = $recentEvent;
            }
        }

        $eventWillShow = array_filter($eventWillShow, function ($event) {
            return $event->Number_of_tickets < $event->Number_of_attendees;
        });

        return response()->json([
            'status' => true,
            'data' => array_unique($eventWillShow),
            'message' => 'events_comming',
        ], 200);
    }

    public function showEventCommingDetails(Request $request)
    {
        $eventId = $request->input('id');
        $event = Event_Comming::findOrFail($eventId);
        $images = $event->image_comming->pluck('image')->toArray();
        $eventImages = array_map(function ($image) {
            return Storage::url($image);
        }, $images);

        $event->images = $eventImages;
        unset($event->image_comming);

        return response()->json([
            'status' => true,
            'data' => $event,
            'message' => 'Event details',
        ], 200);
    }

    public function bookTicket(Request $request)
    {
        $eventId = $request->input('event_id');
        $numberOfTickets = $request->input('number_of_tickets');
        $user = auth()->user();
        $findUser = User::findOrFail($user->id);
        $event = Event_Comming::findOrFail($eventId);
        $priceOfAllTickets = $event->price * $numberOfTickets;
        $currentTickets = $event->Number_of_tickets;
        $allowedAttendees = $event->Number_of_attendees;
        $userCoins = $findUser->coins;

        $remainingTickets = $allowedAttendees - $currentTickets;

        if ($remainingTickets <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'The event is already fully booked. No more tickets are available.',
            ], 400);
        }

        if ($numberOfTickets > $remainingTickets) {
            return response()->json([
                'status' => false,
                'message' => 'The requested number of tickets exceeds the available capacity for this event.',
            ], 400);
        }

        if ($priceOfAllTickets > $findUser->coins) {
            return response()->json([
                'status' => false,
                'message' => 'you dont have enough coins to book these tickets, buy some coins!',
            ], 400);
        }

        $event->Number_of_tickets += $numberOfTickets;
        $event->save();

        for ($i = 0; $i < $numberOfTickets; $i++) {
            $event->users()->attach($user->id);
        }
        $coinsAfterPay = $userCoins - $priceOfAllTickets;
        $findUser->update([
            'coins' => $coinsAfterPay
        ]);
        $message = 'Tickets booked successfully.';

        if ($event->Number_of_tickets == $allowedAttendees) {
            $message = 'The event is fully booked now. No more tickets are available.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'price of all tickt' => $priceOfAllTickets,
            'coins after pay' => $coinsAfterPay
        ], 200);
    }

    public function lastEvents()
    {
        $events = User_Event::with([
            'images:image,title',
            'event:id,categories',
            'place:id,name,price,PhoneNumber,description,tele',
            'ratings',
            'comments' => function ($query) {
                $query->with('user:id,name');
            }
        ])
            ->join('users', 'users.id', '=', 'user_events.user_id')
            ->where('user_events.status', 1)
            ->where('user_events.viewability', 1)
            ->where('user_events.completed', 1)
            ->select(
                'user_events.id',
                'users.name as user_name',
                'user_events.event_id',
                'user_events.place_id',
            )
            ->get()
            ->map(function ($event) {
                $totalRatings = $event->ratings->count();
                $ratingsPercentage = [
                    '1' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                    '2' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                    '3' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                    '4' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                    '5' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
                ];

                $ratingsCount = [
                    '1' => $event->ratings->where('rating', 1)->count(),
                    '2' => $event->ratings->where('rating', 2)->count(),
                    '3' => $event->ratings->where('rating', 3)->count(),
                    '4' => $event->ratings->where('rating', 4)->count(),
                    '5' => $event->ratings->where('rating', 5)->count(),
                ];

                $averageRating = $event->ratings->avg('rating');
                $averageRating = round($averageRating, 1);

                $comments = $event->comments->map(function ($comment) {
                    return [
                        'user_name' => $comment->user->name,
                        'comment' => $comment->comment,
                    ];
                });

                return [
                    'id' => $event->id,
                    'user_name' => $event->user_name,
                    'event' => $event->event,
                    'place' => $event->place->name,
                    'images' => $event->images->map(function ($image) {
                        return [
                            'image' => Storage::url($image->image),
                            'title' => $image->title,
                        ];
                    }),
                    'average_rating' => $averageRating,
                    'total_ratings' => $totalRatings,
                    'ratings_percentage' => $ratingsPercentage,
                    'ratings_count' => $ratingsCount,
                    'comments' => $comments,
                ];
            });

        return $events;
    }

    public function ShowLastEventDetails(Request $request)
    {
        $eventId = $request->input('id');

        $event = User_Event::with([
            'images',
            'event:id,categories',
            'place' => function ($query) {
                $query->with(['address', 'parent']);
            },
            'user:id,name',
            'comments' => function ($query) {
                $query->with('user:id,name');
            },
            'ratings' => function ($query) {
                $query->with('user:id,name');
            },
        ])
            ->find($eventId);

        if ($event) {
            $userData = [
                'user_name' => $event->user->name ?? null,
            ];

            $placeData = $event->place ? [
                'name' => $event->place->name,
                'parent_name' => $event->place->parent ? $event->place->parent->name : null,
                'price' => $event->place->price,
                'PhoneNumber' => $event->place->PhoneNumber,
                'description' => $event->place->description,
                'tele' => $event->place->tele,
                'place' => $event->place->address->name,
            ] : [];

            $commentData = $event->comments->map(function ($comment) {
                return [
                    'user_name' => $comment->user->name,
                    'comment' => $comment->comment,
                ];
            });

            $totalRatings = $event->ratings->count();
            $ratingsPercentage = [
                '1' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 1)->count() / $totalRatings) * 100, 2) : 0,
                '2' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 2)->count() / $totalRatings) * 100, 2) : 0,
                '3' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 3)->count() / $totalRatings) * 100, 2) : 0,
                '4' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 4)->count() / $totalRatings) * 100, 2) : 0,
                '5' => ($totalRatings > 0) ? round(($event->ratings->where('rating', 5)->count() / $totalRatings) * 100, 2) : 0,
            ];

            $ratingsCount = [
                '1' => $event->ratings->where('rating', 1)->count(),
                '2' => $event->ratings->where('rating', 2)->count(),
                '3' => $event->ratings->where('rating', 3)->count(),
                '4' => $event->ratings->where('rating', 4)->count(),
                '5' => $event->ratings->where('rating', 5)->count(),
            ];

            $averageRating = $event->ratings->avg('rating');
            $averageRating = round($averageRating, 1);

            $response = [
                'user_name' => $userData['user_name'],
                'event' => $event->event->categories,
                'place' => $placeData,
                'images' => $event->images->map(function ($image) {
                    return [
                        'image' => Storage::url($image->image),
                        'titel' => $image->titel,
                    ];
                }),
                'comments' => $commentData,
                'ratings' => [
                    'totalRatings' => $totalRatings,
                    'ratingsPercentage' => $ratingsPercentage,
                    'ratingsCount' => $ratingsCount,
                    'averageRating' => $averageRating,
                ],
            ];

            return $response;
        }

        return response()->json(['message' => 'Event not found'], 404);
    }

    public function category(Request $request)
    {
        $categories = Event::select('categories', 'id', 'image')->distinct()->get();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'category_id' => $category->id,
                'name' => $category->categories,
                'image' => Storage::url($category->image)
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'events categories',
        ], 200);
    }

    public function createUserEvent(Request $request)
    {
        $user = auth()->user();
        $userpayments = 0;
        $userDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $finduser = User::findOrFail($user->id);
        $usercoins = $finduser->coins;
        $places = User_event::where('place_id', $request->place_id)->where('date', $userDate)->get();
        if ($places->isEmpty()) {



            if ($request->decoration_id) {
                $decorationprice = Decoration::findOrFail($request->decoration_id);
                $userpayments += $decorationprice->price;
            }


            if ($request->food_id) {
                $foodprice = Food::findOrFail($request->food_id);
                $userpayments += $foodprice->price;
            }
            if ($request->drees_and_makeup_id) {
                $drees_and_makeup_price = Dress_And_Makeup::findOrFail($request->drees_and_makeup_id);
                $userpayments += $drees_and_makeup_price->price;
            }
            if ($request->songer_id) {
                $songerprice = Songer::findOrFail($request->songer_id);
                $userpayments += $songerprice->price;
            }
            if ($request->car_id) {
                $carprice = Car::findOrFail($request->car_id);
                $userpayments += $carprice->price;
            }
            if ($request->photography) {
                $userpayments += 200;
            }
            $placeprice = Place::findOrFail($request->place_id);
            $userpayments += $placeprice->price;
            $coinsAfterPay = $usercoins - $userpayments;
            if ($coinsAfterPay < 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'you do not have enough coins so you cannot make this event,now your coins are: ',
                    'coins' => $coinsAfterPay,
                    'massage2' => 'please buy some coins or change your choices'
                ], 402);
            }
            $userEvent = User_event::create([
                'user_id' => $user->id,
                'event_id' => $request->event_id,
                'place_id' => $request->place_id,
                'decoration_id' => $request->decoration_id,
                'food_id' => $request->food_id,
                'drees_and_makeup_id' => $request->drees_and_makeup_id,
                'songer_id' => $request->songer_id,
                'car_id' => $request->car_id,
                'photography' => $request->photography,
                'date' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
                'status' => $request->status,
            ]);
            $finduser->update([
                'coins' => $coinsAfterPay
            ]);

            return response()->json([
                'coins' => $finduser->coins,
                'your payment' => $userpayments,
                'message' => 'your event created successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'the place you chose is reserved please choose another place'
            ], 409);
        }
    }

    public function updateUserEvent(Request $request)
    {
        $user = auth()->user();
        $user_event_id = $request->input('user_event_id');
        $finduser = User::findOrFail($user->id);
        $usercoins = $finduser->coins;
        $userEvent = User_event::where('id', $user_event_id)->where('user_id', $user->id)->with(
            'place:id,name,price',
            'decoration:id,type,price',
            'food:id,categories,price',
            'drees_and_makeup:id,type,price',
            'songer:id,name,price',
            'car:id,name,price'
        )->first();
        $previousPayment = $userEvent->place->price;

        if ($userEvent->decoration_id) {
            $previousPayment += $userEvent->decoration->price;
        }
        if ($userEvent->food_id) {
            $previousPayment += $userEvent->food->price;
        }
        if ($userEvent->drees_and_makeup_id) {
            $previousPayment += $userEvent->drees_and_makeup->price;
        }
        if ($userEvent->songer_id) {
            $previousPayment += $userEvent->songer->price;
        }
        if ($userEvent->car_id) {
            $previousPayment += $userEvent->car->price;
        }
        if ($userEvent->photography) {
            $previousPayment += 200;
        }
        if (!$userEvent) {
            return response()->json([
                'message' => 'Event not found or you do not have permission to edit this event.'
            ], 404);
        }


        $updateData = [];
        $userpayments = 0;

        if ($request->has('event_id')) {
            $updateData['event_id'] = $request->event_id;
        }
        if ($request->has('place_id')) {
            $updateData['place_id'] = $request->place_id;
            $placeprice = Place::findOrFail($request->place_id);
            $userpayments += $placeprice->price;
        } else {
            $userpayments += $userEvent->place->price;
        }
        if ($request->has('date')) {
            $updateData['date'] = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        }
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }
        if ($request->has('decoration_id')) {
            $updateData['decoration_id'] = $request->decoration_id;
            $decorationprice = Decoration::findOrFail($request->decoration_id);
            $userpayments += $decorationprice->price;
        } else {
            if ($userEvent->decoration_id)
                $userpayments += $userEvent->decoration->price;
        }
        if ($request->has('food_id')) {
            $updateData['food_id'] = $request->food_id;
            $foodprice = Food::findOrFail($request->food_id);
            $userpayments += $foodprice->price;
        } else {
            if ($userEvent->food_id)
                $userpayments += $userEvent->food->price;
        }
        if ($request->has('drees_and_makeup_id')) {
            $updateData['drees_and_makeup_id'] = $request->drees_and_makeup_id;
            $drees_and_makeup_price = Dress_And_Makeup::findOrFail($request->drees_and_makeup_id);
            $userpayments += $drees_and_makeup_price->price;
        } else {
            if ($userEvent->drees_and_makeup_id)
                $userpayments += $userEvent->drees_and_makeup->price;
        }
        if ($request->has('songer_id')) {
            $updateData['songer_id'] = $request->songer_id;
            $songerprice = Songer::findOrFail($request->songer_id);
            $userpayments += $songerprice->price;
        } else {
            if ($userEvent->songer_id)
                $userpayments += $userEvent->songer->price;
        }
        if ($request->has('car_id')) {
            $updateData['car_id'] = $request->car_id;
            $carprice = Car::findOrFail($request->car_id);
            $userpayments += $carprice->price;
        } else {
            if ($userEvent->car_id)
                $userpayments += $userEvent->car->price;
        }
        if ($request->has('photography')) {
            if ($request->photography == 1) {
                $userpayments += 200;
            }
            $updateData['photography'] = $request->photography;
        } else {
            if ($userEvent->photography) {
                $userpayments += 200;
            }
        }

        $difference = $previousPayment - $userpayments;
        $coinsafterPay = $usercoins + $difference;
        if (!empty($updateData) && $coinsafterPay < 0) {
            return response()->json([
                'data' => $coinsafterPay,
                'message' => 'You need to buy more of our coins to complete updating your event.'
            ], 402);
        }


        $userEvent->update($updateData);
        $finduser->update([
            'coins' => $coinsafterPay
        ]);

        return response()->json([
            'message' => 'Your event was updated successfully',
            'coins' => $finduser->coins,
            'updated_event' => $userEvent
        ]);
    }

    public function getMyCard(Request $request)
    {
        $usereventmatrix = [];
        $user = auth()->user();
        $userEvents = User_event::all();
        $userEvents = User_event::where('user_id', $user->id)
            ->where('completed', 0)
            ->with(
                'user:id,name',
                'event:id,categories',
                'place:id,name,price',
                'decoration:id,type,price',
                'food:id,categories,price',
                'drees_and_makeup:id,type,price',
                'songer:id,name,price',
                'car:id,name,price'
            )->get();

        if (!$userEvents) {
            return response()->json([
                'status' => false,
                'message' => 'User event not found',
            ], 404);
        }
        foreach ($userEvents as $userEvent) {
            $usereventmatrix[] = [
                'user_id' => $userEvent->user_id,
                'user name' => $userEvent->user->name,
                'id' => $userEvent->id,
                'event_id' => $userEvent->event_id,
                'event' => $userEvent->event->categories,
                'place_id' => $userEvent->place_id,
                'place' => $userEvent->place->name,
                'decoration_id' => $userEvent->decoration_id,
                'decoration' => $userEvent->decoration->type,
                'food_id' => $userEvent->food_id,
                'food' => $userEvent->food->categories,
                'dress and makeup_id' => $userEvent->drees_and_makeup_id,
                'dress and makeup' => $userEvent->drees_and_makeup->type,
                'songer_id' => $userEvent->songer_id,
                'songer' => $userEvent->songer->name,
                'car_id' => $userEvent->car_id,
                'car' => $userEvent->car->name,
                'date' => $userEvent->date,
                'photography' => $userEvent->photography,
                'status' => $userEvent->status,
                'viewability' => $userEvent->viewability,
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $usereventmatrix,
            'message' => 'This is your card',
        ], 200);
    }

    public function getmyevents(Request $request)
    {
        $user = auth()->user();

        $userEvents = User_event::where('user_id', $user->id)
            ->where('completed', 1)
            ->get();

        $userComings = User_comming::where('user_id', $user->id)
            ->distinct('event_comming_id')
            ->get(['event_comming_id']);

        if ($userEvents->isEmpty() && $userComings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'User events not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'user_events' => $userEvents,
            'user_comings' => $userComings,
        ], 200);
    }

    public function deleteEventFromCart(Request $request)
    {
        $user = auth()->user();
        $user_event_id = $request->input('user_event_id');
        $finduser = User::findOrFail($user->id);
        $usercoins = $finduser->coins;
        $userEvent = User_event::where('id', $user_event_id)->where('user_id', $user->id)->with(
            'place:id,name,price',
            'decoration:id,type,price',
            'food:id,categories,price',
            'drees_and_makeup:id,type,price',
            'songer:id,name,price',
            'car:id,name,price'
        )->first();
        $Payment = $userEvent->place->price;

        if ($userEvent->decoration_id) {
            $Payment += $userEvent->decoration->price;
        }
        if ($userEvent->food_id) {
            $Payment += $userEvent->food->price;
        }
        if ($userEvent->drees_and_makeup_id) {
            $Payment += $userEvent->drees_and_makeup->price;
        }
        if ($userEvent->songer_id) {
            $Payment += $userEvent->songer->price;
        }
        if ($userEvent->car_id) {
            $Payment += $userEvent->car->price;
        }
        if ($userEvent->photography) {
            $Payment += 200;
        }
        $finduser->update([
            'coins' =>  $usercoins + $Payment
        ]);
        $userEventtodelet = User_event::findOrFail($user_event_id)->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'your event deleted successfully , and your coins returned back to you ',
        ], 200);
    }

    public function deleteEventFromMyEvent(Request $request)
    {
        $userEventId = $request->input('user_event_id');
        $userEvent = User_event::findOrFail($userEventId)->delete();
        return response()->json([
            'status' => true,
            'message' => 'your event deleted successfully ',
        ], 200);
    }

    public function lastEventVerification(Request $request)
    {
        $userEventId = $request->input('user_event_id');
        $userEvent = User_event::findOrFail($userEventId);
        $userEvent->update(['completed' => 1]);
        return response()->json([
            'message' => 'added to your event'
        ]);
    }

    public function addFavorite(Request $request)
    {
        $user = auth()->user()->id;
        $type = $request->input('type');

        switch ($type) {
            case 'place':
                $ifExist = Favorite_Place::where('place_id', $request->place_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $favorite = Favorite_Place::create([
                    'user_id' => $user,
                    'place_id' => $request->place_id
                ]);
                break;

            case 'dress_and_makeup':
                $ifExist = Favorite_Dress_And_Makeup::where('drees_and_makeup_id', $request->drees_and_makeup_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $favorite = Favorite_Dress_And_Makeup::create([
                    'user_id' => $user,
                    'drees_and_makeup_id' => $request->drees_and_makeup_id
                ]);
                break;

            case 'songer':
                $ifExist = Favorite_Songer::where('songer_id', $request->songer_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $favorite = Favorite_Songer::create([
                    'user_id' => $user,
                    'songer_id' => $request->songer_id
                ]);
                break;

            case 'car':
                $ifExist = Favorite_Car::where('user_id', $user)
                    ->where('car_id', $request->car_id)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $car = Car::find($request->car_id);

                if (!$car) {
                    return response()->json([
                        'message' => 'Invalid car_id'
                    ], 400);
                }

                $favorite = Favorite_Car::create([
                    'user_id' => $user,
                    'car_id' => $request->car_id
                ]);
                break;

            case 'food':
                $ifExist = Favorite_Food::where('user_id', $user)
                    ->where('food_id', $request->food_id)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $food = Food::find($request->food_id);

                if (!$food) {
                    return response()->json([
                        'message' => 'Invalid food_id'
                    ], 400);
                }

                $favorite = Favorite_Food::create([
                    'user_id' => $user,
                    'food_id' => $request->food_id
                ]);
                break;

            case 'decoration':
                $ifExist = Favorite_decoration::where('decoration_id', $request->decoration_id)
                    ->where('user_id', $user)
                    ->first();

                if ($ifExist) {
                    $ifExist->delete();
                    return response()->json([
                        'message' => ' removed from the fav'
                    ]);
                }

                $favorite = Favorite_decoration::create([
                    'user_id' => $user,
                    'decoration_id' => $request->decoration_id
                ]);
                break;

            default:
                return response()->json([
                    'message' => 'Invalid type'
                ], 400);
        }

        return response()->json([
            'message' => 'Added successfully'
        ]);
    }

    public function getFavoriteItems(Request $request)
    {
        $userId = $request->user()->id;

        $favoritePlaces = Favorite_Place::where('user_id', $userId)
            ->with('place.address')
            ->get()
            ->map(function ($favoritePlace) {
                return [
                    'id' => $favoritePlace->id,
                    'user_id' => $favoritePlace->user_id,
                    'place_id' => $favoritePlace->place_id,
                    'place' => $favoritePlace->place,
                ];
            });

        $favoriteDressesAndMakeups = Favorite_Dress_And_Makeup::where('user_id', $userId)
            ->with('drees_and_makeup')
            ->get()
            ->map(function ($favoriteDressAndMakeup) {
                return [
                    'id' => $favoriteDressAndMakeup->id,
                    'user_id' => $favoriteDressAndMakeup->user_id,
                    'drees_and_makeup_id' => $favoriteDressAndMakeup->drees_and_makeup_id,
                    'drees_and_makeup' => $favoriteDressAndMakeup->drees_and_makeup
                ];
            });

        $favoriteSongers = Favorite_Songer::where('user_id', $userId)
            ->with('songer')
            ->get()
            ->map(function ($favoriteSonger) {
                return [
                    'id' => $favoriteSonger->id,
                    'user_id' => $favoriteSonger->user_id,
                    'songer_id' => $favoriteSonger->songer_id,
                    'songer' => $favoriteSonger->songer
                ];
            });

        $favoriteCars = Favorite_Car::where('user_id', $userId)
            ->with('car')
            ->get()
            ->map(function ($favoriteCar) {
                return [
                    'id' => $favoriteCar->id,
                    'user_id' => $favoriteCar->user_id,
                    'car_id' => $favoriteCar->car_id,
                    'car' => $favoriteCar->car
                ];
            });

        $favoriteDecorations = Favorite_decoration::where('user_id', $userId)
            ->with('decoration')
            ->get()
            ->map(function ($favoriteDecoration) {
                return [
                    'id' => $favoriteDecoration->id,
                    'user_id' => $favoriteDecoration->user_id,
                    'decoration_id' => $favoriteDecoration->decoration_id,
                    'decoration' => $favoriteDecoration->decoration
                ];
            });

        $favoriteFoods = Favorite_Food::where('user_id', $userId)
            ->with('food')
            ->get()
            ->map(function ($favoriteFood) {
                return [
                    'id' => $favoriteFood->id,
                    'user_id' => $favoriteFood->user_id,
                    'food_id' => $favoriteFood->food_id,
                    'food' => $favoriteFood->food
                ];
            });

        $result = [
            "places" => $favoritePlaces->toArray(),
            "dressesAndMakeups" => $favoriteDressesAndMakeups->toArray(),
            "songers" => $favoriteSongers->toArray(),
            "cars" => $favoriteCars->toArray(),
            "decorations" => $favoriteDecorations->toArray(),
            "foods" => $favoriteFoods->toArray()
        ];

        return response()->json($result);
    }

    public function deleteFavorite(Request $request)
    {

        $query = $request->input('query');
        $id = $request->input('favId');
        if ($query == 'place') {
            $results = Favorite_Place::findOrFail($id)->delete();
        } elseif ($query == 'food') {
            $results = Favorite_Food::findOrFail($id)->delete();
        } elseif ($query == 'decoration') {
            $results = Favorite_decoration::findOrFail($id)->delete();
        } elseif ($query == 'car') {
            $results = Favorite_Car::findOrFail($id)->delete();
        } elseif ($query == 'dress_and_makeup') {
            $results = Favorite_Dress_And_Makeup::findOrFail($id)->delete();
        } elseif ($query == 'songer') {
            $results = Favorite_Songer::findOrFail($id)->delete();
        } else {
            return response()->json(['message' => 'Invalid query'], 400);
        }

        return response()->json([
            'data' => $results,
            'massage' => 'your favorite deleted sucessfully',
        ], 200);
    }

    public function buycoins(Request $request)
    {
        $userid = Auth::user()->id;
        $usersmoney = $request->input('yourmoney');
        $coins = $usersmoney / 1000;
        $user = User::findOrFail($userid);
        $userscoins = $user->coins;
        $user->update([
            'coins' => $coins + $userscoins
        ]);
        return response()->json([
            'massage' => 'Done...now your coins are:',
            'data' => $user->coins,

        ], 200);
    }

    public function showUserCoins(Request $request)
    {
        $userid = Auth::user()->id;
        $user = User::findOrFail($userid);
        return response()->json([
            'data' => $user->coins,
        ], 200);
    }

    public function analyzeQuestion($question)
    {
        // Here you can use an external library for text analysis if desired
        // A simple example of manually analyzing the text
        $keywords = explode(' ', $question);
        return $keywords;
    }

    public function findAnswer($question)
    {
        $entities = $this->analyzeQuestion($question);
        $searchQuery = implode(' ', $entities);

        $faq = quistion::whereRaw("MATCH(question, answer, topic, tags) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery])->first();
        //$answer=$faq->answer;
        // if($faq->isEmpty()){
        $costumsearch = $this->customSearch($entities, $question);
        if ($costumsearch) {
            return $faq
                ?  response()->json([
                    'answer' => $faq->answer,
                    'data' =>  $costumsearch,
                ], 200) : response()->json([
                    'data' =>  $costumsearch,
                ], 200);
        } else {
            return response()->json([
                'answer' => $faq->answer,
                'data' => '',
            ], 200);
        }
    }

    private function analyzePartyDetails($description)
    {
        $keywords = [];

        // تحديد نوع الحفلة
        if (str_contains(strtolower($description), 'birthday')) {
            $keywords['type'] = 'birthday';
        } elseif (str_contains(strtolower($description), 'wedding')) {
            $keywords['type'] = 'wedding';
        } elseif (str_contains(strtolower($description), 'graduation')) {
            $keywords['type'] = 'graduation';
        } elseif (str_contains(strtolower($description), 'family party')) {
            $keywords['type'] = 'family party';
        } elseif (str_contains(strtolower($description), 'kids party')) {
            $keywords['type'] = 'kids party';
        }

        // استخراج ميزانية تقريبية
        if (preg_match('/\coins\d+/', $description, $matches)) {
            $keywords['budget'] = intval(str_replace('coins', '', $matches[0]));
        }

        // استخراج عدد الحضور
        if (preg_match('/\b(\d+)\s*(people|guests|attendees|children|adults)\b/i', $description, $matches)) {
            $keywords['guests'] = intval($matches[1]);
        }

        // استخراج تفضيلات الطعام
        if (str_contains(strtolower($description), 'big meal')) {
            $keywords['food'] = 'main meal';
        } elseif (str_contains(strtolower($description), 'food')) {
            $keywords['food'] = 'main meal';
        } elseif (str_contains(strtolower($description), 'meal') || str_contains(strtolower($description), 'meals')) {
            $keywords['food'] = 'main meal';
        } elseif (str_contains(strtolower($description), 'dessert') || str_contains(strtolower($description), 'desserts')) {
            $keywords['food'] = 'dessert';
        } elseif (str_contains(strtolower($description), 'drink') || str_contains(strtolower($description), 'drinks')) {
            $keywords['food'] = 'drink';
        }

        // استخراج تفضيلات الموسيقى
        if (str_contains(strtolower($description), 'romantic music')) {
            $keywords['music'] = 'romantic';
        } elseif (str_contains(strtolower($description), 'upbeat music')) {
            $keywords['music'] = 'upbeat';
        }

        return $keywords;
    }

    private function providePartySuggestions($details)
    {
        $response = "Based on your party details, here are some suggestions:\n";

        // اقتراح الأماكن
        if (isset($details['type']) && in_array($details['type'], ['birthday', 'graduation', 'family party', 'kids party'])) {
            $placesuitables = Place::query()
                ->where('name', 'restaurant')

                ->get();
        } elseif (isset($details['type']) && in_array($details['type'], ['wedding'])) {
            $placesuitables = Place::query()
                ->where('name', 'restaurant')  // جلب الأماكن التي نوعها restaurant
                ->orWhere('name', 'hotel')
                ->orWhere('name', 'hall')
                ->get();
        }
        foreach ($placesuitables as $placesuitable) {
            $places =  Place::query()
                ->where('parent_id', $placesuitable->id)->with('address')->get();
        }
        if ($places->isNotEmpty()) {
            $response .= "Recommended places:\n";
            foreach ($places as $place) {
                $response .= "- {$place->name} at {$place->address->name} with a price of {$place->price}\n";
            }
        }


        // اقتراح الأطعمة
        if (isset($details['food'])) {
            $foodsuitables = Food::query()
                ->where('categories', 'LIKE', "%{$details['food']}%")
                // ->orWhere('name', 'LIKE', "%{$details['food']}%")
                ->get();
            //return $foodsuitables;
            if ($foodsuitables->isNotEmpty()) {
                foreach ($foodsuitables as $foodsuitable) {
                    $foods =  food::query()
                        ->where('parent_id', $foodsuitable->id)->get();
                }
                if ($foods->isNotEmpty()) {
                    $response .= "Recommended foods:\n";
                    foreach ($foods as $food) {
                        $response .= "- {$food->categories} at {$food->price}\n";
                    }
                }
            }
        }

        // اقتراح الموسيقى
        if (isset($details['music'])) {
            $music = Songer::query()
                ->where('name', 'LIKE', "%{$details['music']}%")
                ->get();

            if ($music->isNotEmpty()) {
                $response .= "Recommended music:\n";
                foreach ($music as $track) {
                    $response .= "- {$track->name} at {$track->price}\n";
                }
            }
        }

        return $response;
    }


    private function customSearch($entities, $question = null)
    {
        // إذا كان السؤال يتعلق بوصف الحفلة
        if (
            str_contains(strtolower($question), 'my party') ||
            str_contains(strtolower($question), 'make') ||
            str_contains(strtolower($question), 'organize') ||
            str_contains(strtolower($question), 'making')
        ) {
            $details = $this->analyzePartyDetails($question);
            return $this->providePartySuggestions($details);
        }

        $response = null;

        // Here we analyze the entities and search the appropriate tables
        if (in_array('place', $entities) ||  in_array('places', $entities)) {
            foreach ($entities as $entity) {
                if (str_contains(strtolower($entity), 'near') || str_contains(strtolower($entity), 'in')) {
                    $response = $this->searchPlacesNearby($entities);
                    return $response;
                    break;
                }
            }

            if (!$response) {
                $response = $this->searchPlaces($entities);
            }
        } elseif (
            in_array('food', $entities) || in_array('foods', $entities) ||
            in_array('meal', $entities) || in_array('meals', $entities)
        ) {
            $response = $this->searchFoods($entities);
        } elseif (
            in_array('decoration', $entities) || in_array('decorations', $entities) ||
            in_array('ornament', $entities) || in_array('ornaments', $entities)
        ) {
            $response = $this->searchDecorations($entities);
        } elseif (
            in_array('music', $entities) || in_array('songs', $entities) ||
            in_array('song', $entities) || in_array('sounds', $entities) ||
            in_array('melodies', $entities) || in_array('melody', $entities)
        ) {
            $response = $this->searchMusic($entities);
        } elseif (
            in_array('car', $entities) || in_array('cars', $entities) ||
            in_array('transportation', $entities) || in_array('transportations', $entities)
        ) {
            $response = $this->searchCar($entities);
        }

        return $response;
        //?? "Sorry, I couldn't find an answer to this question.";
    }

    private function searchPlaces($entities)
    {
        // Here we search for suitable places based on the entities
        $places = Place::query()->with('address');

        foreach ($entities as $entity) {
            if (is_numeric($entity)) {
                $result =  $places->orWhere('price', '<=', $entity)->get();
            } else {
                $result = $places->orWhere('name', 'LIKE', "%$entity%")
                    ->orWhereHas('address', function ($query) use ($entity) {
                        $query->where('name', 'LIKE', "%$entity%");
                    })->get();
            }
        }

        // $result = $places->get();

        if ($result->isEmpty()) {
            return "I couldn't find suitable places.";
        }

        $response = "Available places:";
        foreach ($result as $place) {
            if ($place) {
                $response .= "- {$place->name} at {$place->address->name} with a price of {$place->price} \n ";
            }
        }

        return $response;
    }

    private function searchFoods($entities)
    {
        // Here we search for suitable foods based on the entities
        $foods = Food::query();

        foreach ($entities as $entity) {
            if (is_numeric($entity)) {
                $result = $foods->orWhere('price', '<=', $entity)->get();
                //return $result;
            } else {
                $result = $foods->orWhere('categories', 'LIKE', "%$entity%")->get();
            }
        }

        //$result = $foods->get();

        if ($result->isEmpty()) {
            return "I couldn't find suitable foods.";
        }

        $response = "Available foods: \n";
        foreach ($result as $food) {
            if ($food) {
                $response .= "- {$food->categories} with a price of {$food->price}\n";
            }
        }

        return $response;
    }

    private function searchDecorations($entities)
    {
        // Here we search for suitable decorations based on the entities
        $decorations = Decoration::query();

        foreach ($entities as $entity) {
            if (is_numeric($entity)) {
                $result = $decorations->orWhere('price', '<=', $entity)->get();
                //return $result;
            } else {
                $result =  $decorations->orWhere('type', 'LIKE', "%$entity%")->get();
            }
        }

        // $result = $decorations->get();

        if ($result->isEmpty()) {
            return "I couldn't find suitable decorations.";
        }

        $response = "Available decorations: \n";
        foreach ($result as $decoration) {
            $response .= "- {$decoration->type} with a price of {$decoration->price}\n";
        }

        return $response;
    }

    private function searchMusic($entities)
    {
        // Here we search for suitable music based on the entities
        $music = Songer::query();

        foreach ($entities as $entity) {
            if (is_numeric($entity)) {
                $result = $music->orWhere('price', '<=', $entity)->get();
                //return $result;
            } else {
                $result =  $music->orWhere('name', 'LIKE', "%$entity%")->get();
            }
        }

        // $result = $decorations->get();

        if ($result->isEmpty()) {
            return "I couldn't find suitable decorations.";
        }

        $response = "Available music: \n";
        foreach ($result as $music) {
            $response .= "- {$music->name} with a price of {$music->price}\n";
        }

        return $response;
    }

    private function searchCar($entities)
    {
        // Here we search for suitable music based on the entities
        $car = Car::query();

        foreach ($entities as $entity) {
            if (is_numeric($entity)) {
                $result = $car->orWhere('price', '<=', $entity)->get();
                //return $result;
            } else {
                $result =  $car->orWhere('name', 'LIKE', "%$entity%")->get();
            }
        }

        // $result = $decorations->get();

        if ($result->isEmpty()) {
            return "I couldn't find suitable decorations.";
        }

        $response = "Available music: \n";
        foreach ($result as $car) {
            $response .= "- {$car->name} with a price of {$car->price}\n";
        }

        return $response;
    }

    private function searchPlacesNearby($entities)
    {
        $location = Adress::query();

        foreach ($entities as $entity) {

            $locresult =  $location->orWhere('name', 'LIKE', "%$entity%")->first();
        }

        // $result = $decorations->get();

        if (!$locresult) {
            return  "I couldn't find the location specified.";
        }


        // البحث عن جميع الأماكن التي ترتبط بهذا العنوان أو العناوين الفرعية
        $places = Place::where('adress_id', $locresult->id)
            ->orWhereHas('address', function ($query) use ($locresult) {
                $query->where('parent_id', $locresult->id);
            })
            ->get();

        if ($places->isEmpty()) {
            return "I couldn't find any places near the specified location.";
        }

        $response = "Available places near {$locresult->name}: \n";
        foreach ($places as $place) {
            $response .= "- {$place->name} at {$place->address->name} with a price of {$place->price}\n";
        }

        return $response;
    }

    public function getAnswer(Request $request)
    {
        $question = $request->input('question');
        $answer = $this->findAnswer($question);
        //  return $findques ? response()->json([
        //         'answer' => $findques,
        //         'data' =>  $this->customSearch($entities,$question)
        //     ], 200) : response()->json([
        //         'data' =>  $this->customSearch($entities, $question)
        //     ], 200);
        //?? $this->customSearch($entities, $question);

        return response()->json(['answer' => $answer]);
    }
}
