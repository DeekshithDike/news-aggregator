<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\News;

class NewsController extends Controller
{
    public function scrapNews() {

        try {
            $response1 = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get('https://newsapi.org/v2/top-headlines?country=us&apiKey=18749fe727b14d4285bbf253e8f37ac8');
            
            if ($response1->successful()) {
                $newsData1 = $response1->json()['articles'];

                foreach ($newsData1 as $news1) {
                    $publishedAt = isset($news1['publishedAt'])
                        ? date('Y-m-d H:i:s', strtotime($news1['publishedAt']))
                        : null;

                    News::updateOrCreate(
                        ['title' => $news1['title']],
                        [
                            'title'        => $news1['title'],
                            'author'        => $news1['author'] ?? null,
                            'description'  => $news1['description'] ?? null,
                            'url'          => $news1['url'],
                            'image'        => $news1['urlToImage'] ?? "https://static.vecteezy.com/system/resources/previews/000/420/681/original/picture-icon-vector-illustration.jpg",
                            'published_at' => $publishedAt,
                            'source'       => 'newsapi.org', // Provide a default value or use API data
                        ]
                    );
                }
            }


            $response2 = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get('https://content.guardianapis.com/search?api-key=2c3fc069-072a-4f25-9b1b-6c80900f6c03');
            
            if ($response2->successful()) {
                $newsData2 = $response2->json()['response']['results'];

                foreach ($newsData2 as $news2) {
                    $publishedAt = isset($news2['webPublicationDate'])
                        ? date('Y-m-d H:i:s', strtotime($news2['webPublicationDate']))
                        : null;

                    News::updateOrCreate(
                        ['title' => $news2['webTitle']],
                        [
                            'title'        => $news2['webTitle'],
                            'author'        => null,
                            'description'  => null,
                            'url'          => $news2['webUrl'],
                            'image'        => "https://static.vecteezy.com/system/resources/previews/000/420/681/original/picture-icon-vector-illustration.jpg",
                            'published_at' => $publishedAt,
                            'source'       => 'guardianapis.com', // Provide a default value or use API data
                        ]
                    );
                }
            }


            $response3 = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get('https://api.nytimes.com/svc/topstories/v2/arts.json?api-key=GGVQG6M06LNTqDwcICkbWbmkw5wyLpPN');
            
            if ($response3->successful()) {
                $newsData3 = $response3->json()['results'];

                foreach ($newsData3 as $news3) {
                    $publishedAt = isset($news3['published_date'])
                        ? date('Y-m-d H:i:s', strtotime($news3['published_date']))
                        : null;

                    News::updateOrCreate(
                        ['title' => $news3['title']],
                        [
                            'title'        => $news3['title'],
                            'author'        => $news1['byline'] ?? null,
                            'description'  => null,
                            'url'          => $news3['url'],
                            'image'        => $news3['multimedia'][0]['url'] ?? "https://static.vecteezy.com/system/resources/previews/000/420/681/original/picture-icon-vector-illustration.jpg",
                            'published_at' => $publishedAt,
                            'source'       => 'nytimes.com', // Provide a default value or use API data
                        ]
                    );
                }
            }

            return response()->json([
                'message' => 'News fetched successfully!'
            ], 200);

        } catch(Exception $e) {
            \Log::error('New scrap error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function fetchNews() {
        try {
            $results = News::all();

            return response()->json([
                'results' => $results
            ], 200);
        } catch(Exception $e) {
            \Log::error('Fetch news error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
