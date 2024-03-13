import React from "react";
import {
  Location,
  NavigateFunction,
  Params,
  useLocation,
  useNavigate,
  useParams,
} from "react-router-dom";
export interface RouterInterface {
  navigate: NavigateFunction;
  location: Location;
  readonly params: Params<string>;
}
export const withRouter = (Component: React.ComponentType<any>) => {
  const WithRouter = (props: any) => {
    const location = useLocation();
    const navigate = useNavigate();
    const params = useParams();
    return (
      <Component
        {...props}
        location={location}
        navigate={navigate}
        params={params}
      />
    );
  };
  return WithRouter;
};
