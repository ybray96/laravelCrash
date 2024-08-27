<x-layout>
    <h1 class="title">Welcome {{auth()->user()->username }}, you have {{$posts->total()}} posts</h1>

    {{--Create Post form--}}
    <div class="card mb-4">
        <h2 class="font-bold mb-4">Create a new post</h2>
        
        {{--Sesscion Messages--}}
        @if (session('success'))
                <x-flashMsg msg="{{session('success')}}" />
            

            @elseif(session('delete'))
            
                <x-flashMsg msg="{{session('delete')}}" bg="bg-red-500"/>
            
        @endif
        <form action="{{route('posts.store')}}" method="post">
            @csrf
            {{--Post title--}}
            <div class="mb-4">
                <label for="title">Post Title</label>
                <input type="text" name="title" value="{{old('title')}}" class="input @error('title') !ring-red-500 @enderror">
                @error('title')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{--Post Body--}}
            <div class="mb-4">
                <label for="body">Post Title</label>
                <textarea name="body" rows=5 class="input @error('body') ring-red-500 @enderror">{{old('body')}}</textarea>
                @error('body')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            {{--Submit Button--}}
            <button class="btn">Create</button>
        </form>
          
    </div>
    <h1 class="font-bold mb-4">Lataest post</h1> 
    <div class="grid grid-cols-2 gap-6">
        @foreach ($posts as $post)
           <x-postCard :post="$post">
            {{--Delete post--}}
            <form action="{{route('posts.destroy',$post )}}" method="post">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 text-white px-2 py-1 text-xs rounded-md">Delete</button>
            </form>
           </x-postCard>
        @endforeach
     </div>
     <div>
        {{$posts->links()}}
     </div>
</x-layout>