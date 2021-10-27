<?php
namespace App\Http\Controllers\Admin; use App\Library\Response; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class PayWay extends Controller { function get(Request $spe5a184) { $sp32b355 = (int) $spe5a184->input('current_page', 1); $sp048014 = (int) $spe5a184->input('per_page', 20); $spa8a4ff = \App\PayWay::orderBy('sort')->where('type', $spe5a184->input('type')); $sp8336a0 = $spe5a184->input('search', false); $spdbda3a = $spe5a184->input('val', false); if ($sp8336a0 && $spdbda3a) { if ($sp8336a0 == 'simple') { return Response::success($spa8a4ff->get(array('id', 'name'))); } elseif ($sp8336a0 == 'id') { $spa8a4ff->where('id', $spdbda3a); } else { $spa8a4ff->where($sp8336a0, 'like', '%' . $spdbda3a . '%'); } } $sp89bdae = $spe5a184->input('enabled'); if (strlen($sp89bdae)) { $spa8a4ff->whereIn('enabled', explode(',', $sp89bdae)); } $spdf0cee = $spa8a4ff->paginate($sp048014, array('*'), 'page', $sp32b355); return Response::success($spdf0cee); } function edit(Request $spe5a184) { $this->validate($spe5a184, array('id' => 'required|integer', 'type' => 'required|integer|between:1,2', 'name' => 'required|string', 'sort' => 'required|integer', 'channels' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $spb3d6c6 = (int) $spe5a184->post('id'); $spaa0265 = \App\PayWay::find($spb3d6c6); if (!$spaa0265) { if (\App\PayWay::where('name', $spe5a184->post('name'))->exists()) { return Response::fail('名称已经存在'); } $spaa0265 = new \App\PayWay(); } else { if (\App\PayWay::where('name', $spe5a184->post('name'))->where('id', '!=', $spaa0265->id)->exists()) { return Response::fail('名称已经存在'); } } $spaa0265->type = (int) $spe5a184->post('type'); $spaa0265->name = $spe5a184->post('name'); $spaa0265->sort = (int) $spe5a184->post('sort'); $spaa0265->img = $spe5a184->post('img'); $spaa0265->channels = @json_decode($spe5a184->post('channels')) ?? array(); $spaa0265->comment = $spe5a184->post('comment'); $spaa0265->enabled = (int) $spe5a184->post('enabled'); $spaa0265->saveOrFail(); return Response::success(); } function enable(Request $spe5a184) { $this->validate($spe5a184, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $sp8152f4 = $spe5a184->post('ids'); $sp89bdae = (int) $spe5a184->post('enabled'); \App\PayWay::whereIn('id', explode(',', $sp8152f4))->update(array('enabled' => $sp89bdae)); return Response::success(); } function sort(Request $spe5a184) { $this->validate($spe5a184, array('id' => 'required|integer')); $spb3d6c6 = (int) $spe5a184->post('id'); $spaa0265 = \App\PayWay::findOrFail($spb3d6c6); $spaa0265->sort = (int) $spe5a184->post('sort'); $spaa0265->save(); return Response::success(); } function delete(Request $spe5a184) { $this->validate($spe5a184, array('ids' => 'required|string')); $sp8152f4 = $spe5a184->post('ids'); \App\PayWay::whereIn('id', explode(',', $sp8152f4))->delete(); return Response::success(); } }