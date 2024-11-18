

document.addEventListener('DOMContentLoaded', function () {
    if (window.location.href.indexOf("dashboard") > -1) {
        document.querySelector('#dashboard header p:nth-child(2)').style.backgroundColor = '#24285c';
        let first = document.querySelector('#dashboard header p:first-child');
        let second = document.querySelector('#dashboard header p:nth-child(2)');
        if (window.location.href.split('/')[window.location.href.split('/').length - 2] === 'future') {
            first.classList.add('selected');
            second.style.backgroundColor = '#24285c';
            first.style.backgroundColor = '#515ada';
            first.style.color = 'white';
            second.style.color = 'rgba(255, 255, 255, 0.5)';
        }
        else {
            second.classList.add('selected');
            first.style.backgroundColor = '#24285c';
            second.style.backgroundColor = '#515ada';
            first.style.color = 'rgba(255, 255, 255, 0.5)';
            second.style.color = 'white';
        }
        first.addEventListener('click', function () {
            if (first.className !== 'selected') {
                first.classList.add('selected');
                second.style.backgroundColor = '#24285c';
                first.style.backgroundColor = '#515ada';
                first.style.color = 'white';
                second.style.color = 'rgba(255, 255, 255, 0.5)';
                let newurl = window.location.href.split('/');
                newurl[newurl.length - 2] = 'future';
                newurl = newurl.join('/');
                window.location.href = newurl;
            }
        });
        second.addEventListener('click', function () {
            if (second.className !== 'selected') {
                second.classList.add('selected');
                first.style.backgroundColor = '#24285c';
                second.style.backgroundColor = '#515ada';
                first.style.color = 'rgba(255, 255, 255, 0.5)';
                second.style.color = 'white';
                let newurl = window.location.href.split('/');
                console.log(newurl);
                newurl[newurl.length - 2] = 'past';
                console.log(newurl);
                newurl = newurl.join('/');
                console.log(newurl);
                window.location.href = newurl;
            }
        });

        let toggle = document.querySelector('.toggle-control input');
        if (window.location.href.split('/')[window.location.href.split('/').length - 1] === 'organizer') {
            toggle.checked = 'checked';
        }
        else toggle.removeAttribute('checked');

        document.querySelector('.toggle-control').addEventListener('click', function () {
            let past_or_future = document.querySelector('.selected').textContent.split(' ')[0].toLowerCase();
            let toggle = document.querySelector('.toggle-control input');
            if (toggle.checked) {
                window.location.href = `/dashboard/${past_or_future}/organizer`;
            }
            else window.location.href = `/dashboard/${past_or_future}/attendee`;
        });
    }

    if (document.querySelector("#dashboard-users")) {
        let first = document.querySelector('#dashboard-users header p:first-child');
        let second = document.querySelector('#dashboard-users header p:nth-child(2)');
        console.log(window.location.href.endsWith('/adminPage/users'));
        if (window.location.href.endsWith('/adminPage/users')) {
            first.classList.add('selected');
            second.style.backgroundColor = '#24285c';
            first.style.backgroundColor = '#515ada';
            first.style.color = 'white';
            second.style.color = 'rgba(255, 255, 255, 0.5)';
        }
        if (window.location.href.endsWith('/adminPage/changed-users')) {
            second.classList.add('selected');
            first.style.backgroundColor = '#24285c';
            second.style.backgroundColor = '#515ada';
            first.style.color = 'rgba(255, 255, 255, 0.5)';
            second.style.color = 'white';
        }
        first.addEventListener('click', function () {
            if (first.className !== 'selected') {
                first.classList.add('selected');
                second.style.backgroundColor = '#24285c';
                first.style.backgroundColor = '#515ada';
                first.style.color = 'white';
                second.style.color = 'rgba(255, 255, 255, 0.5)';
                window.location.href = '/adminPage/users';
            }
        }
        );
        second.addEventListener('click', function () {
            if (second.className !== 'selected') {
                second.classList.add('selected');
                first.style.backgroundColor = '#24285c';
                second.style.backgroundColor = '#515ada';
                first.style.color = 'rgba(255, 255, 255, 0.5)';
                second.style.color = 'white';
                window.location.href = '/adminPage/changed-users';
            }
        }
        );

    }

    if (document.querySelector('#profile'))
        document.querySelector('#profile').addEventListener('click', function () {
            let dropdown = document.querySelector('#dropdown');
            if (dropdown.className === 'expanded') {
                dropdown.style.height = '0';
                dropdown.querySelectorAll('li').forEach(element => {
                    element.style.opacity = '0';
                })
                dropdown.classList.remove('expanded');
                dropdown.style.opacity = '0'
            }
            else {
                dropdown.style.height = parseFloat(7.5 + 2 * (dropdown.querySelectorAll('li').length - 4)) + 'em';
                dropdown.querySelectorAll('li').forEach(element => {
                    element.style.opacity = '100%';
                })
                dropdown.classList.add('expanded');
                dropdown.style.opacity = '100%';
            }
        });


    document.querySelector('#logo').addEventListener('click', function () {
        window.location.href = '/';
    });

    document.querySelectorAll('#dashboard li, #browse li').forEach(element => {
        element.addEventListener('click', function () {
            window.location.href = `/event/${element.id.toString()}`;
        })
    })
    document.querySelectorAll('#dashboard-users li, #browse-users li').forEach(element => {
        element.addEventListener('click', function () {
            window.location.href = `/user/${element.id.toString()}`;
        })
    })



    if (document.getElementById('searchInput'))
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchTerm = this.value.trim().toLowerCase();
            const eventList = document.querySelectorAll('#events ul li');

            eventList.forEach(event => {
                const title = event.querySelector('span:first-child').textContent.toLowerCase();
                const date = event.querySelector('span:nth-child(2)').textContent.toLowerCase();

                // Check if either title or date contains the search term
                if (title.includes(searchTerm) || date.includes(searchTerm)) {
                    event.style.display = 'flex';
                } else {
                    event.style.display = 'none';
                }
            });
        });

    if (document.querySelector('#event-image') && document.querySelector('#expanded-image')) {
        document.querySelector('#expanded-image').style.width = '0%';
        document.querySelector('#expanded-image svg').style.opacity = '0%';
        document.querySelector('#event-image').addEventListener('click', function () {
            document.querySelector('#expanded-image').style.width = '50%';
            document.querySelector('#expanded-image').style.height = '60%';
            document.querySelector('#expanded-image svg').style.opacity = '100%';
            document.querySelectorAll('body div').forEach(div => { div.style.opacity = '0.5'; })
            document.querySelector('#dropdown').style.opacity = '0';
        });
        document.querySelector('#expanded-image svg').addEventListener('click', function () {
            document.querySelector('#expanded-image').style.width = '0%';
            document.querySelector('#expanded-image').style.height = '0%';
            document.querySelector('#expanded-image svg').style.opacity = '0%';
            document.querySelectorAll('body div').forEach(div => { div.style.opacity = '100%'; })
            document.querySelector('#dropdown').style.opacity = '0';
        });

        function makeCommentEditable(commentId) {
            console.log('asfaf');
            var commentContent = document.querySelector(`.comment-content[data-comment-id='${commentId}']`);
            var originalContent = commentContent.innerHTML;
            var textarea = document.createElement('textarea');
            textarea.id = 'edit-comment';
            textarea.value = originalContent;
            commentContent.parentNode.replaceChild(textarea, commentContent);

            textarea.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    saveCommentEdit(commentId, originalContent);
                }
            });
        }
        function saveCommentEdit(commentId, originalContent) {
            var editedContent = document.getElementById('edit-comment').value;
            console.log(commentId);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/update-comment', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        console.log(response);
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            var newCommentContent = document.createElement('p');
                            newCommentContent.id = 'comment-content';
                            newCommentContent.innerHTML = response.updatedContent;
                            newCommentContent.ondblclick = function () {
                                makeCommentEditable(commentId);
                            };

                            var textarea = document.getElementById('edit-comment');
                            textarea.parentNode.replaceChild(newCommentContent, textarea);
                        } else {
                            console.error('Failed to update the comment');
                        }
                    } else {
                        console.error('HTTP error: ' + this.status);
                    }
                }
            };

            xhr.send(JSON.stringify({ commentId: commentId, content: editedContent }));
        }

        function attachDoubleClickListeners() {
            document.querySelectorAll('.comment-content').forEach(function (element) {
                element.addEventListener('dblclick', function (event) {
                    var commentId = event.target.getAttribute('data-comment-id');
                    makeCommentEditable(commentId);
                });
            });
        }

        attachDoubleClickListeners();

        document.querySelectorAll('.poll input[type="radio"]').forEach(input => {
            input.addEventListener('change', function () {
                var optionId = this.value;
                var pollId = this.name.split('_')[1];
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/submit-poll-vote', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                var data = 'optionId=' + encodeURIComponent(optionId) + '&pollId=' + encodeURIComponent(pollId);
                xhr.send(data);

                xhr.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var response = JSON.parse(this.responseText);
                        if (response.success) {
                            response.voteCounts.forEach(function (voteCount) {
                                var voteCountElement = document.querySelector(`#vote-count-${voteCount.optionId}`);
                                voteCountElement.textContent = `${voteCount.count}`;
                            });
                            alert('Vote submitted successfully');
                        } else {
                            alert('You have already voted');
                        }
                    }
                };
            });
        });

        document.getElementById('add-option').addEventListener('click', function () {
            const optionsContainer = document.getElementById('poll-options');
            const newOptionNumber = optionsContainer.querySelectorAll('input').length + 1;
            const newOptionInput = document.createElement('input');
            newOptionInput.type = 'text';
            newOptionInput.name = 'options[]';
            newOptionInput.placeholder = `Option ${newOptionNumber}`;
            optionsContainer.appendChild(newOptionInput);
        });

        document.getElementById('normal-comment-btn').addEventListener('click', function () {
            document.getElementById('normal-comment-form').style.display = 'block';
            document.getElementById('poll-comment-form').style.display = 'none';
        });

        document.getElementById('poll-comment-btn').addEventListener('click', function () {
            document.getElementById('normal-comment-form').style.display = 'none';
            document.getElementById('poll-comment-form').style.display = 'block';
        });


    }

    if (document.getElementById('save-filters')) {

        var originalSaveFiltersHTML = document.getElementById('filters').innerHTML;


        document.getElementById('save-filters').addEventListener('click', function () {
            var topicSelect = document.getElementById('topic');
            var selectedTopic = topicSelect.value;
            var topicName = topicSelect.options[topicSelect.selectedIndex].text;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/ajax-request', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.send('selectedTopic=' + encodeURIComponent(selectedTopic));


            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    var events = response.events;
                    var eventsList = document.getElementById('events');
                    eventsList.innerHTML = '';
                    var ul = eventsList.querySelector('ul');
                    if (!ul) {
                        ul = document.createElement('ul');
                        eventsList.appendChild(ul);
                    } else {
                        ul.innerHTML = '';
                    }

                    events.forEach(function (event) {
                        var li = document.createElement('li');
                        li.id = event.id;
                        li.innerHTML = '<span>' + event.title + '</span> <span>' + event.eventdatetime + '</span>';
                        ul.appendChild(li);
                    });

                    var filters = document.getElementById('filters');
                    filters.innerHTML = '';
                    var bttn = document.createElement('button');
                    bttn.id = 'filtered';
                    bttn.innerHTML = topicName;
                    filters.appendChild(bttn);

                    bttn.addEventListener('click', function () {
                        var saveFiltersElement = document.getElementById('filters');
                        saveFiltersElement.innerHTML = '';
                        saveFiltersElement.innerHTML = originalSaveFiltersHTML;
                        window.location.href = '/browse';
                    });
                }
            }
        });
    }

    if (document.querySelector('div#attendees.join')) {
        let join = document.querySelector('div#attendees.join');
        join.addEventListener('click', function () {
            if (join.classList.contains('expanded'))
                join.classList.remove('expanded');
            else join.classList.add('expanded');
        });
    }

});
