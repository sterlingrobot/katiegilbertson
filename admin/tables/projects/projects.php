<?php

class tables_projects {

		function block__custom_stylesheets() {
			echo '<style>
							.resultListCell--sort {
								text-align: center !important;
							}
							.sort-icon {
								display: none;
								opacity: 0.6;
								cursor: pointer;
							}
							[data-xataface-query^=\'{"-sort":"is_subproject asc, sort asc"\'] .sort-icon {
								display: inline-block;
							}
							.sort-icon:hover {
								opacity: 1;
							}
							.sort-icon[disabled] {
								opacity: 0.6;
								pointer-events: none;
								cursor: not-allowed;
							}
							.sort-icon[disabled] svg {
								fill: #b9b7b7;
							}
						</style>';
    }

    function sort__renderCell( &$record ) {

    	Dataface_JavascriptTool::getInstance()->import('../../tables/projects/actions/sort-projects.js');
       return '<!--' . __FILE__ . '-->
       					<a class="sort-icon"
     						data-record="' . $record->val('id') . '"
     						data-sort="' . $record->val('sort') . '"
     						data-direction="up"
     						>
       						<svg id="icon-arrow-up" viewBox="0 0 32 32" fill="#436976" height="15" width="15">
										<path d="M16 1l-15 15h9v16h12v-16h9z"></path>
									</svg>
								</a>
       					<a class="sort-icon"
									data-record="' . $record->val('id') . '"
     							data-sort="' . $record->val('sort') . '"
									data-direction="down"
									>
       						<svg id="icon-arrow-down" viewBox="0 0 32 32" fill="#436976" height="15" width="15">
										<path d="M16 31l15-15h-9v-16h-12v16h-9z"></path>
									</svg>
       					</a>';
    }

    function subproject_of__serialize($value) {

        $rec = new Dataface_Record('subprojects_to_projects', array());
        $rec->setValues(array(
        	'projects_id' => $value,
        	'subprojects_id' => $record->getValues('id')
        ));

        return $rec;
    }

    function tags__addTag($record, $value) {

        $rec = new Dataface_Record('tags', array());
        $rec->setValues(array('tag' => $value));

        $rel = new Dataface_Record('tags_to_projects', array());
        $rel->setValues(array(
        	'tags_id' => $rec->getValues(array('tag')),
        	'projects_id' => $record->getValues('id')
        ));

        return $rec;
		}


}
