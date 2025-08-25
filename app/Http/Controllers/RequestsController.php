<?php

namespace App\Http\Controllers;
use App\Models\Auction;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Requests;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RequestsController extends Controller
{
    protected $user;
    protected $permissions;
    protected $request;
    protected $page_data;
    protected $response;
    protected $api = 'd2b5ac1e-0e9c-4f61-b3b8-b1aa6eb99a74';

    public function __construct() {
        $this->request = request();
    }

    public function getRequests()
    {
        $requests = Requests::query()
            ->where('status', 'new')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());
        return view('requests.auction', compact('requests'));
    }

    public function getReview()
    {
        $requests = Requests::query()
            ->where('status', 'in_progress')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());
        return view('requests.review', compact('requests'));
    }
    public function complete()
    {
        $requests = Requests::query()
            ->where('status', 'completed')
            ->whereNotNull('data')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());
        return view('requests.complete', compact('requests'));
    }



    public function addRequest(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'kpp' => 'required|string|in:mkpp,akpp',
            'year' => 'required|integer|min:1990|max:'.date('Y'),
            'license_plate' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'amount' => 'required|string',
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpeg,png,webp|max:5120', // 5MB
        ]);

        $uuid = self::generateRequestId($validated);

        // Начинаем транзакцию для атомарности операций
        DB::beginTransaction();

        try {
            // Создаем или обновляем заявку
            $carRequest = Requests::create(
                [
                    'id' => $uuid,
                    'brand' => $validated['brand'],
                    'model' => $validated['model'],
                    'kpp' => $validated['kpp'],
                    'year' => $validated['year'],
                    'license_plate' => $validated['license_plate'],
                    'phone' => $validated['phone'],
                    'last_name' => $validated['last_name'],
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'status' => 'new',
                    'amount' => $validated['amount'],
                ]
            );

            // Удаляем старые фото, если это обновление заявки
            if ($carRequest->wasRecentlyCreated === false) {
                $this->deleteOldPhotos($carRequest);
            }

            // Сохраняем новые фотографии
            $this->storePhotos($carRequest, $request->file('photos'));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Заявка успешно сохранена',
                'request_id' => $uuid
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении заявки: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удаляет старые фотографии заявки
     */
    protected function deleteOldPhotos(Requests $request)
    {
        // Получаем все фото заявки
        $photos = $request->photos;

        // Удаляем файлы из хранилища
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        // Удаляем записи из БД
        $request->photos()->delete();
    }

    /**
     * Сохраняет фотографии для заявки
     */
    protected function storePhotos(Requests $request, array $photos)
    {
        foreach ($photos as $photo) {
            // Генерируем уникальное имя файла
            $fileName = 'car_' . $request->id . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();

            // Сохраняем файл в storage/app/public/car_photos
            $path = $photo->storeAs('car_photos', $fileName, 'public');

            // Создаем запись в БД
            $request->photos()->create([
                'request_id' => $request->id,
                'path' => $path
            ]);
        }
    }
    public function updateStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,completed'
        ]);
        $auctionRequest = Requests::query()->where('id', $request['id'])->first();
        $auctionRequest->update(['status' => $validated['status']]);


        return response()->json(['success' => true]);
    }

    public function getData($id)
    {
        $request = Requests::with('photos')->findOrFail($id);

        $data = [
            'id' => $request->id,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'kpp' => $request->kpp,
            'license_plate' => $request->license_plate,
            'phone' => $request->phone,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'amount' => $request->amount,
            'status' => $request->status,
            'created_at' => $request->created_at,
            'data' => $request->data,
            'photos' => $request->photos->map(function($photo) {
                return [
                    'path' => asset('storage/' . $photo->path),
                    'original_name' => $photo->original_name ?? null
                ];
            })->toArray(),
        ];

        // Парсим данные осмотра, если они есть
        if ($request->data) {
            try {
                $inspectionData = json_decode($request->data, true);
                if (isset($inspectionData['mileage'])) {
                    $data['mileage'] = $inspectionData['mileage'];
                }
            } catch (\Exception $e) {
                // В случае ошибки парсинга просто пропускаем
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|uuid|exists:requests,id',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1990|max:'.date('Y'),
            'kpp' => 'required|string|in:mkpp,akpp',
            'license_plate' => 'required|string|max:20',
            'mileage' => 'required|integer|min:0|max:1000000',
            'startPrice' => 'required|numeric|min:1000|max:100000000',
            'bidStep' => 'required|numeric|min:100|max:100000',
            'serviceFee' => 'required|integer|min:0|max:50000',
            'data' => 'nullable|json',
        ]);
        try {
            DB::beginTransaction();

            // Создаем аукцион
            $auction = Auction::create([
                'id' => Str::uuid(),
                'request_id' => $validated['id'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'year' => $validated['year'],
                'kpp' => $validated['kpp'],
                'license_plate' => $validated['license_plate'],
                'mileage' => $validated['mileage'],
                'start_price' => $validated['startPrice'],
                'current_price' => $validated['startPrice'],
                'bid_step' => $validated['bidStep'],
                'service_fee' => $validated['serviceFee'],
                'inspection_data' => $validated['data'] ?? null,
                'status' => 'active',
                'start_time' => now()->timezone('Europe/Moscow'),
                'end_date' => now()->timezone('Europe/Moscow')->addMinutes(30)
            ]);

            // Обновляем статус заявки
            $carRequest = Requests::find($validated['id']);
            $carRequest->status = 'auction_created';
            $carRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Аукцион успешно создан',
                'auction_id' => $auction->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании аукциона: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateStatusEnd(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,completed'
        ]);
        $auctionRequest = Requests::query()->where('id', $request['id'])->first();
        $auctionRequest->data = json_encode($request['inspection_data']);
        $auctionRequest->status = $validated['status'];
        $auctionRequest->save();


        return response()->json(['success' => true]);
    }
    protected static function generateRequestId(array $data): string
    {
        $key = implode('|', [
            $data['brand'],
            $data['model'],
            $data['kpp'],
            $data['year'],
            $data['license_plate'],
            $data['phone']
        ]);

        $namespace = Uuid::uuid5(Uuid::NAMESPACE_DNS, config('app.url'));
        return Uuid::uuid5($namespace, $key)->toString();
    }

    public function calculate()
    {

        $request = $this->request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'kpp' => 'required|string|in:mkpp,akpp',
            'year' => 'required|integer|min:1990|max:'.date('Y'),
            'license_plate' => 'required|string'
        ]);

        // Входные данные
        $url = "https://data.tronk.info/avgcarprice.ashx";
        $date_from = new DateTime("1970-1-1");
        $date_to = new DateTime("1970-1-2");
        $request_params = array(
            "key" => $this->api,
            "marka" => $this->request->brand,
            "model" => $this->request->model,
            "year" => $this->request->year,
            "regionid" => 42
        );

        $get_params = http_build_query($request_params);

        // Запрос к серверу
        $response = file_get_contents($url."?".$get_params);
        // Преобразование ответа
        $result = json_decode($response);

        return response()->json([
            'estimate' => round($result->result->minimalAverage, 0),
            'success' => true
        ]);
    }
}
