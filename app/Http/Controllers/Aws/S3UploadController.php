<?php

namespace App\Http\Controllers\Aws;

use App\Http\Controllers\Aws\Support\UsesS3;
use App\Http\Requests\Aws\S3UploadRequest;
use App\Services\Aws\S3Service;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

/**
 * Class S3UploadController
 *
 * @package App\Http\Controllers\Aws
 */
class S3UploadController extends Controller
{
    use UsesS3;

    public function __construct()
    {
        $this->setS3(new S3Service());
    }

    public function __invoke(S3UploadRequest $request)
    {
        /**
         * @var UploadedFile $file
         */
        $file = $request->file('file');
        $fileName = Carbon::now()->timestamp
                    . '_' . str_slug($request->input('file_name'))
                    . '.' . $file->getClientOriginalExtension();

        $s3 = $this->getS3();
        $s3->upload($file, $fileName);
        $s3Response = $s3->getLastResponse();

        $status = Response::HTTP_OK;

        if ($s3Response === null) {
            $response = [
                'errors' => [ 'aws' => $s3->getLastError() ]
            ];

            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            $response = [
                'data' => $s3->getLastResponse()->toArray(),
            ];
        }


        return response()->json($response, $status);
    }
}