new Vue ({
  el: '#AdminEdit',
  data() {
    return {
      name: this.clientId,
      email: null,
      shortName: null,
      emailReplay: null,
      passwd: null,
      user: null,
      edit: null,
      show: null,
      activeClass: 'active'
    };
  },
  methods: {
    submitForm() {
      if (this.edit !== '') {
        axios.post('/admin/update/?edit=' + this.edit, {
          name: this.name,
          email: this.email,
          shortName: this.shortName,
          emailReplay: this.email,
          passwd: this.passwd,
          user: this.user,
        }).then(response => {
          this.response = JSON.stringify(response['data'], null, 2);
        });
      } else {
        axios.post('/admin/save/', {
          name: this.name,
          email: this.email,
          shortName: this.shortName,
          emailReplay: this.email,
          passwd: this.passwd,
          user: this.user,
        }).then(response => {
          this.response = JSON.stringify(response['data'], null, 2);
        });
      }
    },
    editForm: function (k)
    {
      if (k !== '') {
        axios.post('/admin/ajax/student/?edit=' + k).then((args) => {
          this.name = args.data['name'];
          this.shortName = args.data['shortName'];
          this.user = args.data['user'];
          this.edit = args.data['identificate'];
          this.email = args.data['email'];
          this.email_replay = args.data['email'];
          this.show = true;
        });
      } else {
        this.show = true;
      }

    }
  }
});