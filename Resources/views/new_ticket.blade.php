<link rel="stylesheet" href="@PluginAssets('css/style.css')">

<div id="page" class="container">
    <div class="row support-container">
        <div class="col-12 mb-5">
            <h2>{{trans('support_alfiory::user.open_ticket')}}</h2>
            <a style="color: #007bff;" href="{{route('support_alfiory.home')}}">{{trans('support_alfiory::user.return_to_tickets')}}</a>

            <form action="" method="post" class="mt-3">
                @csrf

                <div class="form-group col-md-6">
                    <label>{{trans('support_alfiory::user.category')}}</label>
                    <select class="form-control @error('category') is-invalid @enderror" name="category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" {{ (old('category') == $category->id)? 'selected':'' }}>{{$category->name}}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback"> {{$message}} </div> @enderror
                </div>

                <div class="form-group col-md-8">
                    <label>{{trans('support_alfiory::user.subject')}}</label>
                    <input placeholder="{{trans('support_alfiory::user.subject')}}" value="{{old('subject')}}" class="form-control @error('subject') is-invalid @enderror" name="subject">
                    @error('subject') <div class="invalid-feedback"> {{$message}} </div> @enderror
                </div>

                <div class="form-group col-12">
                    <label>{{trans('support_alfiory::user.message')}}</label>
                    <textarea placeholder="{{trans('support_alfiory::user.message')}}" class="form-control @error('message') is-invalid @enderror" name="message">{{old('message')}}</textarea>
                    @error('message') <div class="invalid-feedback"> {{$message}} </div> @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-md btn-info">{{trans('support_alfiory::user.validate')}}</button>
                </div>

            </form>

        </div>
    </div>
</div>
