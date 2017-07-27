new Vue({
    el: '#manage-vue',
    ready: function() {
      this.fetchMessages();
    },
    data: { 
        users: [],
        myArray:['indigo','pink','purple','blue','cyan','teal','green','lime','success'],
    },

    filters: {
        truncate: function(string, value) 
        {
            return string.substring(0, value);
        },
        humanReadable: function(value) 
        {
            return moment(String(value)).tz('MST').fromNow();
        },
        dateFormat: function(value)
        {
            return moment(String(value)).format("llll");
        }
    },

    methods: {
        fetchMessages: function(e) {
            this.$http.get(baseUrl+'/dashboard/messages').then((messages) => {
                moment.tz.setDefault(messages.data.timeZone);
                if(messages.data.S == 'A')
                {
                    this.$set('users', messages.data.users);
                    this.$set('totalUsers', messages.data.totalUsers);
                    this.$set('investments', messages.data.investments);
                    this.$set('interestPayments', messages.data.interestPayments);
                    this.$set('tickets', messages.data.tickets);
                    this.$set('levelCommisions', messages.data.levelCommisions);
                    console.log(messages.data.levelCommisions);
                }
                else
                {
                    this.$set('levelCommisions', messages.data.levelCommisions);
                    this.$set('interestPayments', messages.data.interestPayments);
                }
          });
        },
    }
})