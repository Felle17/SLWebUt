   <script>
      /**
       * Data som kommer in i komponenten
       * <Comment comment = {...} />
       */  
      export let comment;
   </script>

   <article>
      <article>
         <b>{comment.firstname} {comment.surname} {comment.date} </b>
         <p> {comment.comments_txt} </p>
         </article>
   </article>

   <style>
      article {
         padding: 4px;
         background-color: white;
         margin-top: 5px;
         margin-bottom: 5px;
      }
   </style>
