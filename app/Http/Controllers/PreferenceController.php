<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\AuthorPreference;
use App\Models\SourcePreference;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    public function listAuthPreferences(Request $request) {
        try {
            $results = News::select('author')
                ->whereNotNull('author')
                ->where('author', '<>', '')
                ->groupBy('author')
                ->get();

            return response()->json([
                'results' => $results
            ], 200);
        } catch(Exception $e) {
            \Log::error('List author preference error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function fetchConfiguredAuthPreferences(Request $request) {
        try {
            $results = AuthorPreference::where('users_id', $request->user()->id)->get();

            return response()->json([
                'results' => $results
            ], 200);
        } catch(Exception $e) {
            \Log::error('Fetch author preference error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
        
    }

    public function fetchConfiguredSrcPreferences(Request $request) {
        try {
            $results = SourcePreference::where('users_id', $request->user()->id)->get();

            return response()->json([
                'results' => $results
            ], 200);
        } catch(Exception $e) {
            \Log::error('Fetch source preference error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function saveAuthPreferences(Request $request) {
        try {
            $data = $request->validate([
                'author_pref'   => 'sometimes|array',
                'author_pref.*' => 'required|string'
            ]);
            
            $userId = $request->user()->id;
            
            if (empty($data['author_pref'] ?? [])) {
                AuthorPreference::where('users_id', $userId)->delete();
                
                return response()->json([
                    'message' => 'Author preference updated.'
                ], 200);
            }
            
            $newPrefs = $data['author_pref'];
            
            foreach ($newPrefs as $pref) {
                AuthorPreference::updateOrCreate(
                    ['users_id' => $userId, 'author_pref' => $pref],
                    ['users_id' => $userId, 'author_pref' => $pref]
                );
            }
            
            AuthorPreference::where('users_id', $userId)
                ->whereNotIn('author_pref', $newPrefs)
                ->delete();
            
            AuthorPreference::where('users_id', $userId)->get();

            return response()->json([
                'message' => 'Author preference updated.'
            ], 200);
        } catch(Exception $e) {
            \Log::error('Save author preference error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function saveSrcPreferences(Request $request) {
        try {
            $data = $request->validate([
                'source_pref'   => 'sometimes|array',
                'source_pref.*' => 'required|string'
            ]);
            
            $userId = $request->user()->id;

            if (empty($data['source_pref'] ?? [])) {
                SourcePreference::where('users_id', $userId)->delete();
                
                return response()->json([
                    'message' => 'Source preference updated.'
                ], 200);
            }
            
            $newPrefs = $data['source_pref'];
            
            foreach ($newPrefs as $pref) {
                SourcePreference::updateOrCreate(
                    ['users_id' => $userId, 'source_pref' => $pref],
                    ['users_id' => $userId, 'source_pref' => $pref]
                );
            }
            
            SourcePreference::where('users_id', $userId)
                ->whereNotIn('source_pref', $newPrefs)
                ->delete();
            
            SourcePreference::where('users_id', $userId)->get();

            return response()->json([
                'message' => 'Source preference saved.'
            ], 200);
        } catch(Exception $e) {
            \Log::error('Save source preference error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
