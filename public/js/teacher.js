function removeTeacher(link, parent)
{   
    if(confirm('Czy napewno usunąć?'))
    {
        fetch(link, {
            method: "DELETE",
            credentials: "same-origin",
            headers: {
                "X-CSRF-Token": "{{ csrf_token() }}"
            }
        })
        parent.parentNode.parentNode.remove()
    }
}

function addSubject(link, parent)
{
    let confirm = new Promise((resolve, reject) => {
        document.getElementById('wnd').style.visibility = ''
        document.getElementById('teacher').value = link
        fetch('/subject', {
            method: "GET",
            credentials: "same-origin",
            headers: {
                "X-CSRF-Token": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(res => {
            for(let ll of res)
            {
                var opt = document.createElement('option');
                opt.value = ll.id;
                opt.innerHTML = ll.name;
                document.getElementById('subject').appendChild(opt);
            }
        })
        

        resolve(true);
    })
}

function deleteButton(target)
{
    let result = false;

    if(target.form.elements['subjects[]'].length === undefined) {
        result = target.form.elements['subjects[]'].checked
    } else {
        
        for(var i = 0; i < target.form.elements['subjects[]'].length; i++)
        {
            result = (target.form.elements['subjects[]'][i].checked ? true : false)
            if(result) {
                break;
            }
        }
    }

    target.form.elements['delete'].hidden = !result;
}