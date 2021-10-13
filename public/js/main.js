let articles = document.getElementById("articles");
if(articles){
    articles.addEventListener('click', e =>
        {
           if(e.target.className === 'btn btn-danger delete-article'){
               if(confirm('Are you sure you want to delete this article?')){
                   const  id = e.target.getAttribute('data-id');
                   fetch('/article/delete/' + id,{
                       method: 'DELETE',
                   }).then(res => window.location.reload());
               }
           }
        }
    )
}

let categories = document.getElementById("categories");
if(categories){
    categories.addEventListener('click', e =>
        {
            if(e.target.className === 'btn btn-danger delete-category'){
                if(confirm('Are you sure you want to delete this category?')){
                    const  id = e.target.getAttribute('data-id');
                    fetch('/delete/' + id,{
                        method: 'DELETE',
                    }).then(es => window.location.reload());
                }
            }
        }
    )
}
