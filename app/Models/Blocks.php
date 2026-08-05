<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

class Blocks extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='blocks';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'urlkey',
        'title',
        'content',
        'type',
        'page_id',
        'sortorder',
        'thumbnail_id',
        'image_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'status'            => 'string',
        'urlkey'            => 'string',
        'title'             => 'string',
        'content'           => 'string',
        'type'              => 'integer',
        'page_id'           => 'integer',
        'sortorder'         => 'integer',
        'thumbnail_id'      => 'integer',
        'image_id'          => 'integer',
        'created_by'        => 'integer',
        'updated_by'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:1000',
        'urlkey' => 'required|max:1000|unique:blocks,urlkey'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'urlkey.required' => 'URL Key is required.',
        'urlkey.unique' => 'URL Key already exists.'
    ];


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function status($val)
    {
        $ar=['0' => 'Disable', '1' => 'Active'];
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media','image_id','id');
    }
    
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }

    public function movies()
    {
        return $this->hasMany('App\Models\BlocksMovies','blocks_id','id');
    }
    public function webseries()
    {
        return $this->hasMany('App\Models\BlocksWebseries','blocks_id','id');
    }
    public function shows()
    {
        return $this->hasMany('App\Models\BlocksShows','blocks_id','id');
    }
    
    public function page()
    {
        return $this->belongsTo('App\Models\Post','page_id','id');
    }

    public function genres()
    {
        return $this->hasMany('App\Models\BlocksGenres','blocks_id','id');
    }

    public function languages()
    {
        return $this->hasMany('App\Models\BlocksLanguages','blocks_id','id');
    }

    /*public static function getList()
    {
        $data = Blocks::latest();

        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }
        
        $data =$data->orderBy('sortorder','asc')->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;
    }*/

    public static function getList()
    {
        $data = Blocks::with('page')->latest();

        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }

        $getPageId=app('request')->input('page_id');
        if(!empty($getPageId)){
            $data =$data->where('page_id',$getPageId);
        }
        
        $data =$data->orderBy('sortorder','asc')->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;
    }
    
    public static function getApiList($user_id)
    {
        $baseQuery = Blocks::with([
            'movies' => function ($query) {
                $query->whereHas('movies', function ($q) {
                    $q->where('status', 1);
                    $child = app('request')->input('child');
                    if (!empty($child)) {
                        $q->where('age_restriction', '>=', 13);
                    }
                })->orderBy('id', 'desc');
            },
            'movies.movies',
            'movies.movies.image',
            'movies.movies.thumbnail',
            'movies.movies.usermovies' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
                $profile_id = app('request')->input('profile_id');
                if (empty($profile_id)) { $profile_id = 0; }
                $query->where('profile_id', $profile_id);
            },
            'genres',
            'languages'
        ])->where('status', 1)->where('type', 0);

        $genre_id = app('request')->input('genre_id');
        if (!empty($genre_id)) {
            $baseQuery->whereHas('genres', function ($q) use ($genre_id) {
                $q->where('genre_id', $genre_id);
            });
        }

        $language_id = app('request')->input('language_id');
        if (!empty($language_id)) {
            $baseQuery->whereHas('languages', function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            });
        }

        $getpageid = app('request')->input('page_id');
        if (!empty($getpageid)) {
            $baseQuery->where('page_id', $getpageid);
        }

        $profile_id = app('request')->input('profile_id');
        if (empty($profile_id)) { $profile_id = 0; }

        // Raised default so all blocks for a given page_id fit on page 1 —
        // no more special-casing needed to "guarantee" any block shows up.
        $paginate = app('request')->input('paginate');
        $paginate = empty($paginate) ? 20 : $paginate;

        $data = $baseQuery->orderBy('sortorder', 'asc')
                           ->orderBy('title', 'asc')
                           ->distinct()
                           ->paginate($paginate);

        foreach ($data as $block) {
            $blockTitle = strtolower($block->title ?? '');

            $isUpcomingBlock   = str_contains($blockTitle, 'upcoming');
            $isNewReleaseBlock = str_contains($blockTitle, 'new releases') || str_contains($blockTitle, 'new release');
            $isContinueWatchingBlock = str_contains($blockTitle, 'continue watching');
            $isWatchItAgainBlock     = str_contains($blockTitle, 'watch it again');


            if (!$isUpcomingBlock && !$isNewReleaseBlock && !$isContinueWatchingBlock && !$isWatchItAgainBlock) {
                continue;
            }

            if ($isUpcomingBlock) {
                $allMovies = \App\Models\Movies::with([
                    'image',
                    'thumbnail',
                    'usermovies' => function ($query) use ($user_id, $profile_id) {
                        $query->where('user_id', $user_id);
                        $query->where('profile_id', $profile_id);
                    }
                ])->where('status', 1)
                  ->whereNotNull('release_date')
                  ->get()
                  ->filter(function ($movie) {
                      $releaseDate = $movie->release_date;
                      if (empty($releaseDate)) return false;

                      $datePart = \Carbon\Carbon::parse($releaseDate)->toDateString();
                      $releaseTime = $movie->release_time;

                      if (empty($releaseTime) || $releaseTime === '00:00:00') {
                          $releaseDateTime = \Carbon\Carbon::parse($datePart)->startOfDay();
                      } else {
                          $releaseDateTime = \Carbon\Carbon::parse($datePart . ' ' . $releaseTime);
                      }

                      return $releaseDateTime->isFuture();
                  })
                  ->sortBy(function ($movie) {
                      return \Carbon\Carbon::parse($movie->release_date)->toDateString();
                  });

                $wrappedMovies = $allMovies->map(function ($movie) {
                    $blockMovie = new \App\Models\BlocksMovies();
                    $blockMovie->setRelation('movies', $movie);
                    return $blockMovie;
                });

                $block->setRelation('movies', $wrappedMovies->values());
            }

            if ($isNewReleaseBlock) {
                $daysLimit = \App\Models\CoreConfig::value('new_release_days_limit') ?? 14;
                $daysAgo   = \Carbon\Carbon::now()->subDays($daysLimit)->startOfDay();
                $nowTime   = \Carbon\Carbon::now();

                $newReleaseMovies = \App\Models\Movies::with([
                    'image',
                    'thumbnail',
                    'usermovies' => function ($query) use ($user_id, $profile_id) {
                        $query->where('user_id', $user_id);
                        $query->where('profile_id', $profile_id);
                    }
                ])->where('status', 1)
                  ->whereNotNull('release_date')
                  ->get()
                  ->filter(function ($movie) use ($daysAgo, $nowTime) {
                      $releaseDate = $movie->release_date;
                      if (empty($releaseDate)) return false;

                      $datePart = \Carbon\Carbon::parse($releaseDate)->toDateString();
                      $releaseTime = $movie->release_time;

                      if (empty($releaseTime) || $releaseTime === '00:00:00') {
                          $releaseDateTime = \Carbon\Carbon::parse($datePart)->startOfDay();
                      } else {
                          $releaseDateTime = \Carbon\Carbon::parse($datePart . ' ' . $releaseTime);
                      }

                      return $releaseDateTime->between($daysAgo, $nowTime);
                  })
                  ->sortByDesc(function ($movie) {
                      return \Carbon\Carbon::parse($movie->release_date)->toDateString();
                  });

                $wrappedMovies = $newReleaseMovies->map(function ($movie) {
                    $blockMovie = new \App\Models\BlocksMovies();
                    $blockMovie->setRelation('movies', $movie);
                    return $blockMovie;
                });

                $block->setRelation('movies', $wrappedMovies->values());
            }
            
            if ($isContinueWatchingBlock) {
                $continueWatching = \App\Models\UsersMovies::with([
                        'movies.image',
                        'movies.thumbnail',
                        'movies.usermovies' => function ($q) use ($user_id, $profile_id) {
                            $q->where('user_id', $user_id)->where('profile_id', $profile_id);
                        }
                    ])
                    ->where('user_id', $user_id)
                    ->where('profile_id', $profile_id)
                    ->where('watching', 1)
                    ->where('watched_percent', '<', 90)
                    ->whereHas('movies', function ($q) {
                        $q->where('status', 1);
                    })
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->pluck('movies')
                    ->filter()
                    ->values();

                $wrappedMovies = $continueWatching->map(function ($movie) {
                    $bm = new \App\Models\BlocksMovies();
                    $bm->setRelation('movies', $movie);
                    return $bm;
                });

                $block->setRelation('movies', $wrappedMovies->values());
            }

            if ($isWatchItAgainBlock) {
                $watchItAgain = \App\Models\UsersMovies::with([
                        'movies.image',
                        'movies.thumbnail',
                        'movies.usermovies' => function ($q) use ($user_id, $profile_id) {
                            $q->where('user_id', $user_id)->where('profile_id', $profile_id);
                        }
                    ])
                    ->where('user_id', $user_id)
                    ->where('profile_id', $profile_id)
                    ->where('watched_percent', '>=', 90)
                    ->whereHas('movies', function ($q) {
                        $q->where('status', 1);
                    })
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->pluck('movies')
                    ->filter()
                    ->values();

                $wrappedMovies = $watchItAgain->map(function ($movie) {
                    $bm = new \App\Models\BlocksMovies();
                    $bm->setRelation('movies', $movie);
                    return $bm;
                });

                $block->setRelation('movies', $wrappedMovies->values());
            }
        }
        $filtered = $data->getCollection()->reject(function ($block) {
            $t = strtolower($block->title ?? '');
            $isCW = str_contains($t, 'continue watching');
            $isWA = str_contains($t, 'watch it again');
            return ($isCW || $isWA) && (!$block->relationLoaded('movies') || $block->movies->isEmpty());
        });

        $data->setCollection($filtered->values());

        return $data;
    }
    public static function getwebseriesApiList($user_id)
    {
        $data = Blocks::with([
            'webseries.webseries' => function ($q) {
                $q->where('status', 1);
                $child = app('request')->input('child');
                if (!empty($child)) {
                    $q->where('age_restriction', '>=', 13);
                }
            },
            // All seasons + episodes ordered latest first
            'webseries.webseries.seasons' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'webseries.webseries.seasons.episodes' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'webseries.webseries.seasons.episodes.image',
            'webseries.webseries.seasons.episodes.medium',
            'webseries.webseries.seasons.episodes.thumbnail',
            'webseries.webseries.seasons.episodes.episode_users' => function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            },
            'genres',
            'languages'
        ]);

        $data = $data->where('status', 1)->latest();

        if ($genre_id = request('genre_id')) {
            $data->whereHas('genres', fn($q) => $q->where('genre_id', $genre_id));
        }
        if ($lang_id = request('language_id')) {
            $data->whereHas('languages', fn($q) => $q->where('language_id', $lang_id));
        }
        if ($page_id = request('page_id')) {
            $data->where('page_id', $page_id);
        }

        return $data->orderBy('sortorder', 'asc')
                    ->orderBy('title', 'asc')
                    ->paginate(request('paginate', 5));
    }
    /*public static function getWebseriesWatchApiList($user_id, $webseries_id)
    {
        return Banner::with([
            'webseries' => function ($q) use ($webseries_id) {
                $q->where('id', $webseries_id)->where('status', 1);
            },

            // ADD this
            'webseries.seasons.episodes',

            'webseries.seasons.episodes.episode_users' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }
        ])
        ->where('webseries_id', $webseries_id)
        ->where('status', 1)
        ->latest()
        ->first();
    }*/
}