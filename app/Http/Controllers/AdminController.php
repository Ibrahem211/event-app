<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Decoration;
use App\Models\Dress_And_Makeup;
use App\Models\Event_comming;
use App\Models\Food;
use App\Models\Image_comming;
use App\Models\image_last;
use App\Models\Image_Place;
use App\Models\Place;
use App\Models\Session;
use App\Models\Songer;
use App\Models\User;
use App\Models\User_event;
use App\Models\UserLogout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function Creat_Event(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'price' => 'required|integer',
                'location' => 'required|string',
                'description' => 'required|string',
                'Number_of_attendees' => 'required|integer',
                'Number_of_tickets' => 'required|integer',
                'date' => 'required',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'error' => $validate->errors()
            ], 401);
        }

        $eventComing = new Event_comming();
        $eventComing->name = $request->name;
        $eventComing->type = $request->type;
        $eventComing->price = $request->price;
        $eventComing->location = $request->location;
        $eventComing->description = $request->description;
        $eventComing->Number_of_attendees = $request->Number_of_attendees;
        $eventComing->Number_of_tickets = $request->Number_of_tickets;
        $eventComing->date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $eventComing->save();

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                $imageData = [
                    'event_commings_id' => $eventComing->id,
                    'image' => $imagePath
                ];
                $images[] = Image_comming::create($imageData);
            }
        }

        return response()->json(['msg' => 'The event has been created successfully'], 201);
    }

    public function createCategoryPlace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:places,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $place = Place::create([
            'name' => $request->name,
            'parent_id' => null,
            'adress_id' => $request->adress_id,
            'price' => $request->price,
            'PhoneNumber' => $request->PhoneNumber,
            'description' => $request->description,
            'tele' => $request->tele,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'category created successfully',
        ], 201);
    }

    public function CreatePlaces(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'required|exists:places,id',
            'adress_id' => 'required|exists:adresses,id',
            'price' => 'required|numeric',
            'PhoneNumber' => 'required|string|unique:places,PhoneNumber',
            'description' => 'required|string',
            'tele' => 'required|string|unique:places,tele',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentPlace = Place::find($request->parent_id);

        if ($parentPlace->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent place must not have a parent.',
            ], 400);
        }

        $place = Place::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'adress_id' => $request->adress_id,
            'price' => $request->price,
            'PhoneNumber' => $request->PhoneNumber,
            'description' => $request->description,
            'tele' => $request->tele,
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('place-images', 'public');
                $images[] = ['place_id' => $place->id, 'image' => $imagePath];
            }
            Image_Place::insert($images);
        }

        return response()->json([
            'status' => true,
            'message' => 'Place created successfully',
        ], 201);
    }

    public function CreateSongers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'required|exists:songers,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentSonger = Songer::find($request->parent_id);

        if ($parentSonger->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent songer must not have a parent.',
            ], 400);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('songer-images', 'public');
        }

        $songer = Songer::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Songer created successfully',
        ], 201);
    }

    public function CreateCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:cars,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentCar = null;
        if ($request->parent_id) {
            $parentCar = Car::find($request->parent_id);
        }

        if ($parentCar && $parentCar->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent car must not have a parent.',
            ], 400);
        }

        $imagePath = $request->file('image')->store('car-images', 'public');

        $car = Car::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Car created successfully',
        ], 201);
    }

    public function createDecoration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'parent_id' => 'nullable|exists:decorations,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentDecoration = null;
        if ($request->parent_id) {
            $parentDecoration = Decoration::find($request->parent_id);
        }

        if ($parentDecoration && $parentDecoration->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent decoration must not have a parent.',
            ], 400);
        }

        $imagePath = $request->file('image')->store('decoration-images', 'public');

        $decoration = Decoration::create([
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Decoration created successfully',
        ], 201);
    }

    public function createFood(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories' => 'required|string',
            'parent_id' => 'nullable|exists:food,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentFood = null;
        if ($request->parent_id) {
            $parentFood = Food::find($request->parent_id);
        }

        if ($parentFood && $parentFood->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent food must not have a parent.',
            ], 400);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('food-images', 'public');
        }

        $food = Food::create([
            'categories' => $request->categories,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Food created successfully',
        ], 201);
    }

    public function createDressAndMakeup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'parent_id' => 'nullable|exists:dress__and__makeups,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Dress_And_Makeup::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $request->file('image')->store('dress-and-makeup-images', 'public');

        $item = Dress_And_Makeup::create([
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Dress and Makeup item created successfully',
        ], 201);
    }

    public function updateDressAndMakeup(Request $request)
    {
        $id = $request->input('id');
        $item = Dress_And_Makeup::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string',
            'parent_id' => 'nullable|exists:dress__and__makeups,id',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Dress_And_Makeup::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('dress-and-makeup-images', 'public');
        }

        $data = [
            'type' => $request->type ?? $item->type,
            'parent_id' => $request->parent_id ?? $item->parent_id,
            'price' => $request->price ?? $item->price,
            'description' => $request->description ?? $item->description,
            'image' => $imagePath,
        ];

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Dress and Makeup item updated successfully',
        ], 200);
    }

    public function updateFood(Request $request)
    {
        $id = $request->input('id');
        $item = Food::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'categories' => 'nullable|string',
            'parent_id' => 'nullable|exists:food,id',
            'price' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Food::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('food-images', 'public');
        }

        $data = [
            'categories' => $request->categories ?? $item->categories,
            'parent_id' => $request->parent_id ?? $item->parent_id,
            'price' => $request->price ?? $item->price,
            'description' => $request->description ?? $item->description,
            'image' => $imagePath,
        ];

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Food item updated successfully',
        ], 200);
    }

    public function updateDecoration(Request $request)
    {
        $id = $request->input('id');
        $item = Decoration::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string',
            'parent_id' => 'nullable|exists:decorations,id',
            'price' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Decoration::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('decoration-images', 'public');
        }

        $data = [
            'type' => $request->type ?? $item->type,
            'parent_id' => $request->parent_id ?? $item->parent_id,
            'price' => $request->price ?? $item->price,
            'description' => $request->description ?? $item->description,
            'image' => $imagePath,
        ];

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Decoration item updated successfully',
        ], 200);
    }

    public function updateCar(Request $request)
    {
        $id = $request->input('id');
        $item = Car::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'parent_id' => 'nullable|exists:cars,id',
            'price' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Car::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('car-images', 'public');
        }

        $data = [
            'name' => $request->name ?? $item->name,
            'parent_id' => $request->parent_id ?? $item->parent_id,
            'price' => $request->price,
            'description' => $request->description ?? $item->description,
            'image' => $imagePath,
        ];

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Car item updated successfully',
        ], 200);
    }

    public function updateSongers(Request $request)
    {
        $id = $request->input('id');
        $item = Songer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:songers,id',
            'price' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentItem = null;
        if ($request->parent_id) {
            $parentItem = Songer::find($request->parent_id);
        }

        if ($parentItem && $parentItem->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent item must not have a parent.',
            ], 400);
        }

        $imagePath = $item->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $imagePath = $request->file('image')->store('songer-images', 'public');
        }

        $data = [
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? $item->parent_id,
            'price' => $request->price,
            'description' => $request->description ?? $item->description,
            'image' => $imagePath,
        ];

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Songer item updated successfully',
        ], 200);
    }

    public function updatePlaces(Request $request)
    {
        $id = $request->input('id');
        $place = Place::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'parent_id' => 'nullable|exists:places,id',
            'adress_id' => 'nullable|exists:adresses,id',
            'price' => 'nullable|integer',
            'PhoneNumber' => 'nullable|string|unique:places,PhoneNumber,' . $place->id,
            'description' => 'nullable|string',
            'tele' => 'nullable|string|unique:places,tele,' . $place->id,
            'images' => 'nullable|array|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $parentPlace = null;
        if ($request->parent_id) {
            $parentPlace = Place::find($request->parent_id);
        }

        if ($parentPlace && $parentPlace->parent_id !== null) {
            return response()->json([
                'status' => false,
                'message' => 'The parent place must not have a parent.',
            ], 400);
        }

        $data = [
            'name' => $request->name ?? $place->name,
            'parent_id' => $request->parent_id ?? $place->parent_id,
            'adress_id' => $request->adress_id ?? $place->adress_id,
            'price' => $request->price ?? $place->price,
            'PhoneNumber' => $request->PhoneNumber ?? $place->PhoneNumber,
            'description' => $request->description ?? $place->description,
            'tele' => $request->tele ?? $place->tele,
        ];

        $place->update($data);

        if ($request->hasFile('images')) {
            $existingImages = $place->images()->pluck('id')->toArray();
            Image_Place::whereIn('id', $existingImages)->delete();

            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('place-images', 'public');
                Image_Place::create([
                    'image' => $imagePath,
                    'place_id' => $place->id,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Place updated successfully',
        ], 200);
    }

    public function numberOfSuppliers(Request $request)
    {
        $i = 0;
        $numberOfPlaces = Place::where('parent_id', '!=', null)->get();
        foreach ($numberOfPlaces as $place) {
            $i++;
        }
        return response()->json([
            'message' => 'number of suppliers:',
            'data' => $i,

        ], 201);
    }

    public function numberOfUsers(Request $request)
    {
        $totalUsers = User::count();
        $activeTime = now()->subMinutes(config('session.lifetime'))->timestamp;
        $activeSessions = Session::where('last_activity', '>=', $activeTime)->count();
        $loggedOutUsers = UserLogout::count();

        return response()->json([
            'message' => 'number of  total users(users logged in ):',
            'total' =>  $totalUsers,
            'message2' => 'number of logged out :',
            'loggedout' => $loggedOutUsers,
            'message3' => 'number of active session:',
            'active' => $totalUsers - $loggedOutUsers,
        ]);
    }

    public function numberOfEvents(Request $request)
    {
        $numberOfEvents = User_event::count();
        return response()->json([
            'message' => 'number of events created by users:',
            'data' => $numberOfEvents,

        ], 201);
    }
    public function userEventsWithTotalPayments(Request $request)
    {
        $userEventmat = [];
        $userEvents = User_event::with(
            'user:id,name',
            'event:id,categories',
            'place:id,name,price',
            'decoration:id,type,price',
            'food:id,categories,price',
            'drees_and_makeup:id,type,price',
            'songer:id,name,price',
            'car:id,name,price'
        )->get();
        foreach ($userEvents as $userEvent) {
            $userEventmat[] = [
                'user name' => $userEvent->user->name,
                'event' => $userEvent->event->categories,
                'place' => $userEvent->place->name,
                'place price' => $userEvent->place->price,
                'decoration' => $userEvent->decoration->type,
                'decoration price' => $userEvent->decoration->price,
                'food' => $userEvent->food->categories,
                'food price' => $userEvent->food->price,
                'dress and makeup' => $userEvent->drees_and_makeup->type,
                'dress and makeup price' => $userEvent->drees_and_makeup->price,
                'songer' => $userEvent->songer->name,
                'songer price' => $userEvent->songer->price,
                'car' => $userEvent->car->name,
                'car price' => $userEvent->car->price,
                'date' => $userEvent->date,
                'photography' => $userEvent->photography,
                'status' => $userEvent->status,
                'viewability' => $userEvent->viewability,
                'completed' => $userEvent->completed,
                'total payment' => $userEvent->place->price + $userEvent->decoration->price +
                    $userEvent->food->price + $userEvent->drees_and_makeup->price +
                    $userEvent->songer->price + $userEvent->car->price,

            ];
        }
        return response()->json([
            'message' => 'events made by useres:',
            'date' => $userEventmat
        ]);
    }

    public function changeviewability(Request $request)
    {
        $userEventId = $request->input('user_event_id');
        $userEvent = User_event::findOrFail($userEventId);
        if ($userEvent->status == 0) {
            return response()->json([
                'message' => 'the event is privet so you cant show it in home page'
            ]);
        }
        if ($userEvent->viewability == 0) {
            $userEvent->update(['viewability' => 1]);
            return response()->json([
                'message' => 'the event will be showen in home page'
            ]);
        }
        $userEvent->update(['viewability' => 0]);
        return response()->json([
            'message' => 'the event will be removed from home page'
        ]);
    }

    public function ShowAllUser()
    {
        $users = User::all();

        $usersWithImage = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'PhoneNumber' => $user->PhoneNumber,
                'is_admin' => $user->is_admin,
                'coins' => $user->coins,
                'image' => $user->image ? Storage::url($user->image) : null,
            ];
        });

        return response()->json($usersWithImage);
    }

    public function DeleteUser(Request $request)
    {
        $userEventId = $request->input('user_id');
        $userEvent = User::findOrFail($userEventId)->delete();
        return response()->json([
            'status' => true,
            'message' => 'user deleted successfully ',
        ], 200);
    }

    public function deleteServes(Request $request)
    {

        $query = $request->input('query');
        $id = $request->input('Id');
        if ($query == 'place') {
            $results = Place::findOrFail($id)->delete();
        } elseif ($query == 'food') {
            $results = Food::findOrFail($id)->delete();
        } elseif ($query == 'decoration') {
            $results = Decoration::findOrFail($id)->delete();
        } elseif ($query == 'car') {
            $results = Car::findOrFail($id)->delete();
        } elseif ($query == 'dress_and_makeup') {
            $results = Dress_And_Makeup::findOrFail($id)->delete();
        } elseif ($query == 'songer') {
            $results = Songer::findOrFail($id)->delete();
        } else {
            return response()->json(['message' => 'Invalid query'], 400);
        }

        return response()->json([
            'data' => $results,
            'massage' => 'serves deleted sucessfully',
        ], 200);
    }

    public function addPhotosToLastEvent(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'photos.*' => 'required|image',
            'title' => 'required|string',
        ]);

        $userEvent = User_event::findOrFail($validatedData['id']);

        $images = [];
        foreach ($validatedData['photos'] as $key => $photo) {
            $imagePath = $photo->store('event-images', 'public');
            $images[] = [
                'title' => $validatedData['title'],
                'image' => $imagePath,
                'user_event_id' => $userEvent->id,
            ];
        }

        image_last::insert($images);

        return response()->json([
            'message' => 'Photos added successfully',
        ], 200);
    }
}
