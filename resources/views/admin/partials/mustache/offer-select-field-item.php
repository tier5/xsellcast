<script id="offer-select-field-item" type="x-tmpl-mustache">
	<tr data-name="{{row.title}}" data-id="{{row.id}}" onclick="{{selectEvent}}(this)">
		<td><img src="{{row.thumbnail}}" width="75" /></td>
		<td>
			<label class="label label-{{row.badge}}">{{row.author_type_human}}</label> {{row.status_human}}<br/>
			<strong class="text-navy">{{row.title }}</strong>
		</td>
		<td>{{row.updated_at_human}}</td>
	</tr>
</script>