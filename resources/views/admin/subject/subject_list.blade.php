@foreach ($subjectTypes as $subjectType) 
	@php
    $checked = $subjectType->is_class_sub?'checked':'';
  @endphp

  <tr>
    <td>{{ $subjectType->code }}</td>
    <td>  
    	<input type="checkbox" class="checkbox" {{ $checked }} onchange="if (this.checked) {
        $('#Optional{{ $subjectType->id }}').prop('checked', true)
        $('#Compulsory{{ $subjectType->id }}').prop('checked', true)
      }else{
        $('#Compulsory{{ $subjectType->id }}').prop('checked', false)
        $('#Optional{{ $subjectType->id }}').prop('checked', false)

      }" id="subject{{ $subjectType->id }}" name="subject_id[]" value="{{ $subjectType->id }}">
    </td>
    <td>{{ $subjectType->subject_name }}</td>
    
    <td >
      <label class="radio-inline">
      <input type="radio" {{ $subjectType->opt_id==1?'checked':'' }} id="Compulsory{{ $subjectType->id }}" onclick="$('#subject{{ $subjectType->id }}').prop('checked', true)" name="value[{{ $subjectType->id }}]" class="{{ str_replace(' ', '_', strtolower('Compulsory')) }}"   value="1"> Compulsory </label>
    </td>
    <td >
      <label class="radio-inline">
      <input type="radio" {{ $subjectType->opt_id==2?'checked':'' }} id="Optional{{ $subjectType->id }}" onclick="$('#subject{{ $subjectType->id }}').prop('checked', true)" name="value[{{ $subjectType->id }}]" class="{{ str_replace(' ', '_', strtolower('Optional')) }}"   value="2"> Optional </label>
    </td>

    
  </tr> 
    
@endforeach 