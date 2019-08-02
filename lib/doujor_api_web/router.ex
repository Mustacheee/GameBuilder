defmodule DoujorApiWeb.Router do
  use DoujorApiWeb, :router

  pipeline :api do
    plug :accepts, ["json"]
  end

  scope "/api", DoujorApiWeb do
    pipe_through :api
  end
end
