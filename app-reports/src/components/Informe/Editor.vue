<template>
   <div>
     <vue-editor v-model="content" :editorToolbar='customToolbar'></vue-editor>
   </div>
 </template>

 <script>
import {
    VueEditor
} from "vue2-editor";

export default {
    components: {
        VueEditor
    },

    data() {
        return {
            content: "",
            customToolbar: [
                ["bold", "italic", "underline", "strike"],
                [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'align': []
                }],

                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }], // outdent/indent

                [{
                    'size': ['small', false, 'large', 'huge']
                }], // custom dropdown


                [{
                    'color': []
                }, {
                    'background': []
                }], // dropdown with defaults from theme
                [{
                    'font': []
                }],


            ]

        };
    }
  
};
</script>