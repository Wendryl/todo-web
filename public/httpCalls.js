export async function loadTasks() {
  const result = await fetch("http://localhost:8080/api/tasks")
    .then((response) => {
      if (response.status == 401) throw new Error('Unauthorized');
      return response.json();
    })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function createTask(newTask) {
  const result = await fetch("http://localhost:8080/api/tasks", {
    method: "POST",
    body: JSON.stringify(newTask),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function updateTask(id, newTask) {
  return fetch(`http://localhost:8080/api/tasks/${id}`, {
    method: "PUT",
    body: JSON.stringify(newTask),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });
}

export async function deleteTask(id) {
  return fetch(`http://localhost:8080/api/tasks/${id}`, {
    method: "DELETE",
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });
}

export async function authenticate(login, password) {
  const result = await fetch("http://localhost:8080/api/auth/login", {
    method: "POST",
    body: JSON.stringify({
      login,
      password,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}

export async function logout() {
  const result = await fetch("http://localhost:8080/api/auth/logout", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((data) => data)
    .catch((error) => {
      throw error;
    });

  return result;
}




