<script src="http://cdn.jsdelivr.net/vue/1.0.14/vue.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://raw.githubusercontent.com/matfish2/vue-tables/master/dist/vue-tables.min.js"></script>

<div id="news">

    <a href="{{ route('admin.' . $module . '.create') }}" class="btn-add"><i class="fa fa-plus-circle"></i><span class="sr-only">New</span></a>
    <h1>
        <span>@{{ tableData.length }} @choice('news::global.news', 2)</span>
    </h1>

    <div class="btn-toolbar">
        @include('core::admin._lang-switcher')
    </div>

    <div class="table-responsive">
      <v-client-table :data="tableData" :options="options"></v-client-table>
    </div>

</div>

<script>
    Vue.use(VueTables.client, {
        compileTemplates: true,
        //highlightMatches: true,
        pagination: {
            // dropdown:true
            // chunk:5
        },
        //filterByColumn: true,
        texts: {
            filter: "@lang('global.Search')"
        },
        skin:'table-condensed'
    });

    new Vue({
        el: "#news",
        methods: {
            deleteMe: function (id) {
                alert("Delete " + id);
            }
        },
        data: {
            tableData: {!! News::all([], true) !!},
            options: {
                columns: ['id', 'status', 'thumb', 'date', 'title'],
                dateFormat: 'yyyy mm dd',
                perPage: 25,
                perPageValues: [25, 50, 100, 500, 1000, 5000],
                headings: {
                    id: '',
                    status: 'Status',
                    thumb: 'Image',
                    date: 'Date',
                    title: 'Title'
                },
                templates: {
                    id: '<a href="javascript:void(0);" @click="$parent.deleteMe({id})"><span class="fa fa-remove"></span></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-default btn-xs" href="news/{id}/edit">Edit</i></a>',
                    status: '<div class="btn btn-xs btn-link" @click="action()">' +
                        '<span class="fa switch" :class="{status} ? \'fa-toggle-on\' : \'fa-toggle-off\'"></span>' +
                    '</div>',
                    thumb: '<img src="{thumb}">'
                },
                orderBy: {
                    column: 'date',
                    ascending: false
                }
            }
        }
    });
</script>
