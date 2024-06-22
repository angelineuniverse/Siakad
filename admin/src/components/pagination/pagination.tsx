import clsx from "clsx";
import React, { Component, ReactNode } from "react";
import { ModelPagination } from "./model";
import { paginateController, dots } from "./controller";
const Icon = React.lazy(() => import("../icon/icon"));

class Pagination extends Component<ModelPagination> {
  state: Readonly<{
    paginate: Array<any>;
  }>;
  constructor(props: any) {
    super(props);
    this.state = {
      paginate: [],
    };
  }

  componentDidUpdate(prevProps: ModelPagination): void {
    if (prevProps.totalPage !== this.props.totalPage) {
      const pages = paginateController(
        this.props.totalPage,
        this.props.currentPage
      );
      this.setState({
        paginate: pages,
      });
    }
  }

  classpaginatePage(index: number, currentPage: number) {
    if (index + 1 !== 5 && currentPage > 3) {
      return currentPage - 4 + index + 1 === currentPage
        ? "bg-blue-600 text-white border-blue-300"
        : "border-gray-300";
    } else {
      return index + 1 === currentPage && currentPage <= 3
        ? "bg-blue-600 text-white border-blue-300"
        : "border-gray-300";
    }
  }
  render(): ReactNode {
    return (
      <>
        {this.props.totalPage && (
          <div
            className={clsx(
              "flex justify-start gap-x-1 font-intermedium text-xsm"
            )}
          >
            <button
              className={clsx(
                "border rounded p-1.5 border-gray-300",
                this.props.currentPage > 1
                  ? "text-black hover:border-blue-500"
                  : "text-gray-400"
              )}
              disabled={this.props.currentPage === 1}
            >
              <Icon
                width={17}
                height={17}
                icon="arrow_prev"
                color={this.props.currentPage > 1 ? "#000000" : "#A9A9A9"}
              />
            </button>
            {this.state.paginate.map((pageNumber) => {
              if (pageNumber === dots) {
                return <li className="mt-auto">&#8230;</li>;
              }
              return (
                <button
                  className={clsx(
                    "border rounded py-2 px-3 hover:border-blue-500"
                    // this.classpaginatePage(index, this.props.currentPage)
                  )}
                  key={`${pageNumber}-count`}
                >
                  {pageNumber}
                </button>
              );
            })}
            <button
              className={clsx(
                " border rounded p-1.5 border-gray-300",
                this.props.currentPage !== this.props.totalPage
                  ? "text-black hover:border-blue-500"
                  : "text-gray-400"
              )}
              disabled={this.props.totalPage === 1}
            >
              <Icon
                width={17}
                height={17}
                icon="arrow_next"
                color={this.props.totalPage > 1 ? "#000000" : "#A9A9A9"}
              />
            </button>
          </div>
        )}
      </>
    );
  }
}

export default Pagination;
