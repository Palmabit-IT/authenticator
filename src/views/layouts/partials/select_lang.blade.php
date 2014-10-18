<select name="preferred_lang" class="form-control">
  @foreach ($languages as $lang =>$desc)
    <option value="{{$lang}}">{{$desc}}</option>
  @endforeach
</select>