{{-- regular object attribute --}}
@php
    if(!empty($column['entity'])){
        if($entry->{$column['entity']}){
            $entity = $entry->{$column['entity']};

            $video_url = isset($column['attribute']) && isset($entity->{$column['attribute']}) ? $entity->{$column['attribute']} : "";
            $video_image = isset($column['image']) && isset($entity->{$column['image']}) ? $entity->{$column['image']} : "";
            $video_title = isset($column['title']) && isset($entity->{$column['title']}) ? $entity->{$column['title']} : "";

            $video = json_decode(json_encode([
                'url' => $video_url, 
                'provider' => 'custom',
                "title" => $video_title ,
                "image" => $video_image ,
            ]));
        }

       
    }
    else if( !empty($entry->{$column['name']}) ) {

        // if attribute casting is used, convert to object
        if (is_array($entry->{$column['name']})) {
            $video = (object)$entry->{$column['name']};
        }/* elseif (is_string($entry->{$column['name']})) {
            $video = json_decode($entry->{$column['name']});
        } */else {
            $video = json_decode(json_encode([
                'url' => $entry->{$column['name']}, 
                'provider' => 'custom',
                "title" => isset($column['title']) ? $entry->{$column['title']} : '',
                "image" => isset($column['image']) ? $entry->{$column['image']} : '',
            ]));
        }
        $bgColor = isset($video->provider) && $video->provider == 'vimeo' ? '#00ADEF' : '#DA2724';
    }
@endphp


<span>
    @if( isset($video) )
    <a target="_blank" href="{{$video->url}}" title="{{$video->title}}">
        <img src="{{$video->image}}" alt="{{$video->title}}" style="height: 25px; border-top-right-radius: 3px; border-bottom-right-radius: 3px;" />
    </a>
        
    @else
    -
    @endif
</span>
