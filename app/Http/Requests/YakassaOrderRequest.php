<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class YakassaOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'object' => 'required|array',
            'object.id' => 'required', 
            'object.status' => 'required', 
            'object.metadata.order_id'  => 'required'    
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                response()->json(['data' => $errors], 422)
            );
        }

        parent::failedValidation($validator);
    }

}


// {
//     "type": "notification",
//     "event": "payment.waiting_for_capture",
//     "object": {
//       "id": "22d6d597-000f-5000-9000-145f6df21d6f",
//       "status": "waiting_for_capture",
//       "paid": true,
//       "amount": {
//         "value": "2.00",
//         "currency": "RUB"
//       },
//       "authorization_details": {
//         "rrn": "10000000000",
//         "auth_code": "000000",
//         "three_d_secure": {
//           "applied": true
//         }
//       },
//       "created_at": "2018-07-10T14:27:54.691Z",
//       "description": "Заказ №72",
//       "expires_at": "2018-07-17T14:28:32.484Z",
//       "metadata": {},
//       "payment_method": {
//         "type": "bank_card",
//         "id": "22d6d597-000f-5000-9000-145f6df21d6f",
//         "saved": false,
//         "card": {
//           "first6": "555555",
//           "last4": "4444",
//           "expiry_month": "07",
//           "expiry_year": "2021",
//           "card_type": "MasterCard",
//         "issuer_country": "RU",
//         "issuer_name": "Sberbank"
//         },
//         "title": "Bank card *4444"
//       },
//       "refundable": false,
//       "test": false
//     }
//   }