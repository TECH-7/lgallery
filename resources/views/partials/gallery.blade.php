<section id="gallery" class="scroller">
    <ul class="row grid">
        @foreach ($photos as $photo)
        <li class="col-xs-6 col-md-3 col-md-offset-0 col-xs-offset-0">
            <article class="item">
                <figure class="img-wrap"><a href="{{ $photo->url }}" class="gal" title="{{ $photo->caption }}"><img src="{{ $photo->thumbnailUrl }}" alt="{{ $photo->alt_text }}"></a></figure>
                <div class="caption" style="display:none">{{ $photo->caption }}</div>
                <footer>
                    <section class="tags pull-left">
                        <nav>
                        @foreach($photo->tags as $tag)
                            <a href="{{ action('PhotosController@tag', $tag->name) }}" class="btn btn-default btn-sm" role="button">{{ $tag->name }}</a>
                        @endforeach
                        </nav>
                    </section>                                
                    <section class="img-icon pull-right">
                        <a href="{{ action('PhotosController@createComment', ['id' => $photo->id]) }}" class="text-icon" title="Add Comments">
                            {{ $photo->comments()->count() ? $photo->comments()->count() : '+' }} 
                        </a>
                    </section>
                </footer>
            </article>
        </li>
        @endforeach
    </ul>
    {{ $photos->links() }}
</section>


@section('footer')
<script src="{{ asset('js/jquery.jscroll.min.js') }}"></script>
<script>
    function prepareGallery() {
        $(".img-wrap .gal").colorbox({rel:'img', maxWidth:'100%'});
    
		// Caption to display on hover
        $('.img-wrap').hover(
			function() {
				if ($(this).next().html().trim()!='')
					$(this).next().slideDown();
			},
			function() {
				$(this).next().hide();
			}
		);        

        //hides the default paginator for jscroll lazy load
        $('ul.pagination:visible:first').hide();
    }
    
    $(document).ready(function(){
        prepareGallery();

        //init jscroll and tell it a few key configuration details
        //nextSelector - this will look for the automatically created
        //contentSelector - this is the element wrapper which is cloned and appended with new paginated data
        $('section.scroller').jscroll({
            //debug: true,
            autoTrigger: true,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'section.scroller ul',
            padding: 0,
            callback: function() {
                prepareGallery();
            }
        });
	
    });
</script>
@append