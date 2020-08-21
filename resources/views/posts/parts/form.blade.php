<div class="form-group">
    <input type="text" name="title" class="form-control" required value="{{old('title') ?? $post->title ?? ''}}">
</div>
<div class="form-group">
    <textarea name="description" rows="10" class="form-control" required>{{old('description') ??$post->description ?? ''}}</textarea>
</div>
<div class="form-group">
    <input name="img" type="file">
</div>
